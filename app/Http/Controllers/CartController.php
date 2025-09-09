<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Ingredient;
use App\Models\Pizza;
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

            $cart = $request->session()->get('cart_id');

            $pizza = Pizza::find($request->input('pizza_id'));
            if (!$pizza) return ApiResponse::error('The item does not exists.');

            $inserted = CartItem::insertOrIgnore([
                'id' => Str::uuid7(),
                'cart_id' => $cart,
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
            $cart = $request->session()->get('cart_id');

            $toUpdateId = $request->input('cart_item_id');

            $cartItem = CartItem::where('cart_id', $cart)->where('id', $toUpdateId)->first();

            if (!$cartItem) return ApiResponse::error('The item does not exists.');

            $updated = collect($request->input('updated', []));

            if ($updated->quantity) $cartItem->quantity = $updated->quantity;
            if ($updated->size) $cartItem->size = $updated->size;
            if ($updated->ingredients) $cartItem->ingredients = $updated->ingredients;

            $cartItem->save();

            return ApiResponse::success("Cart updated successfully.", $cart);
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
}
