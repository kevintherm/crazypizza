<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
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
            $cart = Cart::find($request->session()->get('cart_id'));

            if (!$cart) {
                return ApiResponse::error('Cart not found.');
            }

            $updates = $request->input('items', []);

            foreach ($updates as $update) {
                $cartItem = $cart->items()->find($update['id']);

                if ($cartItem) {
                    $cartItem->quantity = $update['quantity'];
                    $cartItem->size = $update['size'];
                    $cartItem->ingredients = $update['ingredients'] ?? [];
                    $cartItem->save();
                }
            }

            $this->calculateSubtotal($cart);

            return ApiResponse::success("Cart updated successfully.", [
                'subtotal' => $cart->total,
            ]);
        }, __FILE__);
    }

    public function removeFromCart(Request $request)
    {
        return $this->safe(function () use ($request) {
            $cart = Cart::find($request->session()->get('cart_id'));

            $cart->items()->where('pizza_id', $request->pizza_id)->delete();

            return ApiResponse::success("Pizza removed from cart successfully.", $cart);
        }, __FILE__);
    }

    private function calculateSubtotal(Cart $cart)
    {
        $subtotal = new Money('0');

        foreach ($cart->items as $item) {
            // pizza price
            $subtotal = $subtotal->add($item->pizza->price->mul($item->quantity));

            // toppings price
            if (isset($item->ingredients)) {
                $ingredients = Ingredient::find(collect($item->ingredients)->pluck('id'));
                foreach ($ingredients as $ingredient) {
                    $subtotal = $subtotal->add($ingredient->price_per_unit->mul(collect($item->ingredients)->firstWhere('id', $ingredient->id)['quantity']));
                }
            }
        }

        $cart->total = $subtotal;
        $cart->save();
    }
}
