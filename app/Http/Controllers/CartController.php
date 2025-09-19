<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Ingredient;
use App\Models\Pizza;
use App\ValueObjects\Money;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function getCount(Request $request)
    {
        return $this->safe(function () use ($request) {

            $cart = Cart::find($request->session()->get('cart_id'));

            return ApiResponse::success('Success', $cart->items()->count());

        }, __FILE__);
    }

    public function addToCart(Request $request)
    {
        return $this->safe(function () use ($request) {

            $cart = Cart::find($request->session()->get('cart_id'));

            $pizza = Pizza::find($request->input('pizza_id'));
            if (!$pizza)
                return ApiResponse::error('The item does not exists.');

            $inserted = CartItem::insertOrIgnore([
                'id' => Str::uuid7(),
                'cart_id' => $cart->id,
                'pizza_id' => $pizza->id,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $inserted == 0 ?
                ApiResponse::error('Item is already on cart.') :
                ApiResponse::success("Pizza added to cart successfully.", $cart->items()->count());

        }, __FILE__);
    }

    public function updateCart(Request $request)
    {
        return $this->safe(function () use ($request) {

            $validated = $request->validate([
                'items' => 'array',
                'items.*.id' => 'required|uuid|exists:cart_items,id',
                'items.*.quantity' => 'required|integer|min:1|max:100',
                'items.*.size' => 'nullable|string|max:50',
                'items.*.ingredients' => 'nullable|array',
                'items.*.ingredients.*.id' => 'required|uuid|exists:ingredients,id',
                'items.*.ingredients.*.quantity' => 'required|integer|min:1|max:20',
                'coupon' => 'nullable|string|max:50',
            ]);

            $cartId = $request->session()->get('cart_id');
            $cart = Cart::with('items')->find($cartId);
            if (!$cart) {
                return ApiResponse::error('Cart not found.');
            }

            $updates = $validated['items'] ?? [];
            $couponCode = $validated['coupon'] ?? null;

            $discount = new Money('0');
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)
                    ->where('is_active', true)
                    ->first();

                if (!$coupon) {
                    return ApiResponse::error('Invalid coupon.');
                }

                $cart->coupon_code = $coupon->code;
                $cart->save();

                $discount = $coupon->discount;
            }

            foreach ($updates as $update) {
                $cartItem = $cart->items()->where('id', $update['id'])->first();

                if ($cartItem) {
                    $cartItem->quantity = $update['quantity'];
                    $cartItem->size = $update['size'] ?? $cartItem->size;
                    $cartItem->ingredients = $update['ingredients'] ?? $cartItem->ingredients;
                    $cartItem->save();
                }
            }

            $cart->refresh();

            $subtotal = $this->calculateSubtotal($cart->items);

            $cart->total = $subtotal;
            $cart->save();

            $shipping = new Money('0');
            $tax = new Money('0');

            $total = Money::sum([$shipping, $tax, $subtotal])->sub($discount);
            if ($total->cmp('0') < 0) {
                $total = new Money('0');
            }

            return ApiResponse::success("Cart updated successfully.", [
                'shipping' => $shipping,
                'tax' => $tax,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ]);
        }, __FILE__);
    }

    public function removeFromCart(Request $request)
    {
        return $this->safe(function () use ($request) {
            $validated = $request->validate([
                'item_id' => 'required|uuid|exists:cart_items,id',
            ]);
            $cart = Cart::find($request->session()->get('cart_id'));

            $cart->items()->where('id', $validated['item_id'])->delete();

            return ApiResponse::success("Pizza removed from cart successfully.", $cart);
        }, __FILE__);
    }

    private function calculateSubtotal($items)
    {
        $subtotal = new Money('0');

        foreach ($items as $item) {
            // pizza price
            $subtotal = $subtotal->add($item->pizza->price->mul($item->quantity));

            // toppings price
            if (isset($item->ingredients)) {
                $ingredients = Ingredient::find(collect($item->ingredients)->pluck('id'));
                foreach ($ingredients as $ingredient) {
                    $subtotal = $subtotal->add(
                        $ingredient->price_per_unit->mul(
                            collect($item->ingredients)->firstWhere('id', $ingredient->id)['quantity']
                        )
                    );
                }
            }
        }

        return $subtotal;
    }
}
