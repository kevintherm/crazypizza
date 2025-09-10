<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use DB;
use Error;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function checkout(Request $request)
    {
        try {

            DB::beginTransaction();

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|string|max:255',
                'customer_phone' => 'nullable|string',
                'delivery_address' => 'required|string|max:1000',
            ]);

            $cartId = $request->session()->get('cart_id');
            $cart = Cart::with(['items', 'items.pizza'])->find($cartId);

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('welcome')->with('error', 'Your cart is empty.');
            }

            $lineItems = [];
            foreach ($cart->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->pizza->name,
                        ],
                        'unit_amount' => (int) ($cart->total->toFloat() * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $order = Order::create([
                'invoice_number' => Order::generateInvoiceNumber(),
                'stripe_session_id' => null,
                'total_amount' => $cart->total->toFloat(),
                'status' => Order::STATUS['pending'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'delivery_address' => $validated['delivery_address'],
                'customer_phone' => $validated['customer_phone'],
                'json' => null
            ]);

            $stripeParams = [
                [
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success', ['invoice' => $order->invoice_number]),
                    'cancel_url' => route('checkout.cancel', ['invoice' => $order->invoice_number]),
                    'customer_email' => $validated['customer_email'],
                    'metadata' => [
                        'cart_id' => $cartId,
                    ],
                ]
            ];

            $checkoutSession = $this->stripe->checkout->sessions->create($stripeParams);

            $order->stripe_session_id = $checkoutSession->id;
            $order->json = [
                'stripe_params' => $stripeParams,
                'cart_items' => $cart->items->toArray(),
                'cart' => $cart->toArray()
            ];
            $order->save();

            DB::commit();

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            DB::rollBack();
            Helper::LogThrowable($request, __FILE__, $e);
            return redirect()->route('cart')->with('error', 'An error occurred during payment processing.');
        }
    }

    public function success(Request $request)
    {
        try {

            DB::beginTransaction();

            $invoice = $request->input('invoice', null);
            if (!$invoice)
                return redirect()->route('cart')->with('error', 'Something went wrong. Please try again later.');

            $order = Order::where('invoice_number', $invoice)->first();
            if (!$order)
                return redirect()->route('cart')->with('error', "Something went wrong. Please try again later. (Invoice: $invoice)");

            if ($order->status != Order::STATUS['pending'])
                throw new NotFoundHttpException();

            $session = $this->stripe->checkout->sessions->retrieve($order->stripe_session_id);
            if (!$session)
                return redirect()->route('cart')->with('error', 'Invalid session.');

            // CHANGE ORDER STATUS
            if ($session->payment_status === "paid") {
                $order->status = Order::STATUS['paid'];
                $order->save();

                // OTHER ACTIONS: Send Email, Notification, Etc
            }

            $cartId = isset($order->json['cart']['id']) ? $order->json['cart']['id'] : null;
            $cart = Cart::with('items')->find($cartId);

            if ($cart) {
                $cart->items()->delete();
                $cart->delete();
            }

            $request->session()->forget('cart_id');

            $newCart = Cart::create();
            $request->session()->put('cart_id', $newCart->id);

            DB::commit();
            return redirect()->route('order.confirmation', ['invoice' => $order->invoice_number]);

        } catch (NotFoundHttpException $e) {
            DB::rollBack();
            abort(404);
        } catch (\Throwable $e) {
            DB::rollBack();
            Helper::LogThrowable($request, __FILE__, $e);
            return redirect()->route('cart')->with('error', 'An error occurred during payment processing.');
        }
    }

    public function cancel(Request $request)
    {
        $invoice = $request->input('invoice', null);
        if (!$invoice) return redirect()->route('cart');

        if ($invoice) {
            $order = Order::where('invoice_number', $invoice)->first();
            if ($order) {
                $order->status = Order::STATUS['cancelled'];
                $order->save();
            }
        }

        return redirect()->route('order.confirmation', ['invoice' => $invoice]);
    }
}

