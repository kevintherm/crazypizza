<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Ingredient;
use App\Models\Order;
use App\ValueObjects\Money;
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
                'notes' => 'nullable|string|max:1000'
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
                            'description' => Str::limit($item->pizza->description, 100) . "\n'Contains: " . implode(', ', $item->pizza->ingredients->pluck('name')->toArray()) . ".",
                            'images' => [
                                // asset("storage/{$item->pizza->image}")
                                'https://plus.unsplash.com/premium_photo-1757322537445-892532434841?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                            ]
                        ],
                        'unit_amount_decimal' => $item->pizza->price->toFloat() * 100,
                    ],
                    'quantity' => $item->quantity,
                ];

                foreach ($item->ingredients ?? [] as $ingredientItem) {
                    $ingredient = Ingredient::find($ingredientItem['id']);
                    if (!$ingredient)
                        continue;

                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => "Topping: $ingredient->name",
                                'description' => Str::limit($ingredient->description, 100),
                                'images' => [
                                    // asset("storage/{$ingredient->image}")
                                    'https://plus.unsplash.com/premium_photo-1757322537445-892532434841?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                                ]
                            ],
                            'unit_amount_decimal' => $ingredient->price_per_unit->toFloat() * 100,
                        ],
                        'quantity' => $ingredientItem['quantity'],
                    ];
                }
            }

            $discounts = [];

            $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();

            if ($coupon) {
                $discounts['Coupon'] = $coupon->discount;
            }

            $totalDiscount = Money::sum(array_values($discounts));
            $totalAmount = $cart->total->sub($totalDiscount);

            if ($totalAmount->cmp('0') < 0) {
                $totalAmount = new Money('0');

                $lineItems = [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'FREE ORDER',
                                'description' => 'Congratulations! Your order total is $0.00.',
                            ],
                            'unit_amount_decimal' => 0,
                        ],
                        'quantity' => 1,
                    ]
                ];
            }

            $order = Order::create([
                'invoice_number' => Order::generateInvoiceNumber(),
                'stripe_session_id' => null,
                'status' => Order::STATUS['pending'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'delivery_address' => $validated['delivery_address'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount,
                'coupon_code' => $coupon->code ?? null,
                'total_discount' => $totalDiscount,
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
        if (!$invoice)
            return redirect()->route('cart');

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

