<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Pizza;
use Illuminate\Http\Request;
use SessionHandler;

class GuestController extends Controller
{
    public function welcome()
    {
        $top6 = Pizza::with('ingredients')->latest()->take(6)->get();

        return view('welcome', compact('top6'));
    }

    public function pizzas()
    {
        $pizzas = Pizza::with('ingredients')->latest()->get();

        return view('pizzas', compact('pizzas'));
    }

    public function cart(Request $request)
    {
        $cart = Cart::find($request->session()->get('cart_id'))->load(['items', 'items.pizza']);
        if (!$cart) redirect('/');

        $cartItems = $cart->items;

        // filter items


        return view('cart', compact('cart'));
    }
}
