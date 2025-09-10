<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Pizza;
use App\Models\PizzaReview;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SessionHandler;
use Validator;

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
        if (!$cart)
            redirect('/');

        return view('cart', compact('cart'));
    }

    public function orderConfirmation($invoice)
    {
        $order = Order::where('invoice_number', $invoice)->with('reviews')->firstOrFail();

        return view('order-confirmation', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $invoice = $request->input('invoice', null);
        $order = Order::where('invoice_number', $invoice)->first();

        if (!$invoice || !$order)
            return redirect()->route('order.track')->with('error', 'Order not found.');

        return redirect()->route('order.confirmation', ['invoice' => $invoice]);
    }

    public function rateOrder(Request $request, $invoice)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'ratings' => 'required|array',
            'ratings.*.rating' => 'required|integer|min:1|max:5',
            'ratings.*.comment' => 'nullable|string|max:500',
        ], []);

        foreach ($request->input('ratings', []) as $key => $value) {
            Validator::make(
                ['key' => $key],
                ['key' => ['required', 'uuid', Rule::exists('pizzas', 'id')]]
            )->validate();
        }

        $validated = $validator->validated();

        $order = Order::where('invoice_number', $invoice)->firstOrFail();

        if ($order->reviewed) return redirect()->back();

        if ($validated['email'] != $order->customer_email) return redirect()->back()->with('error', 'Email is incorrect. Please try again.');

        foreach ($validated['ratings'] as $productId => $data) {
            $pizza = Pizza::find($productId);

            if (!$pizza) continue;
            Review::create([
                'order_id' => $order->id,
                'pizza_id' => $productId,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);

            $pizza->rating = $pizza->reviews->average('rating');
            $pizza->save();
        }

        $order->reviewed = true;
        $order->save();

        return redirect()->back()->with('success', 'Thank you for your feeback!');
    }
}
