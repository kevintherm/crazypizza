<x-app :title="'Order ' . $order->invoice">
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-xl">
                <div class="text-center">

                    @switch($order->status)
                        @case(\App\Models\Order::STATUS['pending'])
                            <svg class="mx-auto h-16 w-16 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        @break

                        @case(\App\Models\Order::STATUS['arrived'])
                        @case(\App\Models\Order::STATUS['completed'])

                        @case(\App\Models\Order::STATUS['paid'])
                            <svg class="mx-auto h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @break

                        @case(\App\Models\Order::STATUS['shipped'])
                            <svg class="mx-auto h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @break

                        @default
                            <svg class="mx-auto h-16 w-16 text-red-500" class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                    @endswitch

                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        @switch($order->status)
                            @case(\App\Models\Order::STATUS['paid'])
                                Thank you for your order!
                            @break

                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        Your order was {{ $order->status == \App\Models\Order::STATUS['paid'] ? 'successful and your order is being prepared' : $order->status }}.
                    </p>
                </div>

                <div class="mt-10 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Order Details</h2>
                        <dl class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-900 dark:text-white">Invoice Number:</dt>
                                <dd class="font-mono">{{ $order->invoice_number }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-900 dark:text-white">Order Date:</dt>
                                <dd>{{ $order->created_at->format('F j, Y H:m') }}</dd>
                            </div>

                            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total Amount:</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">{{ $order->total_amount->format() }}</dd>
                            </div>
                            @if (isset($order->json['cart_items']) && is_array($order->json['cart_items']))
                                @foreach ($order->json['cart_items'] as $item)
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-900 dark:text-white">{{ $item['pizza']['name'] }}:</dt>
                                        <dd>{{ $item['quantity'] }}x {{ (new \App\ValueObjects\Money($item['pizza']['price']))->format() }}{{ $item['ingredients'] ? '*' : '' }}</dd>
                                    </div>
                                @endforeach
                            @endif
                        </dl>
                        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                            You can use your invoice number to track your order. If you have any questions, please contact our support team.
                        </p>
                    </div>
                </div>

                <div x-data="{
                    url: @js(route('order.confirmation', ['invoice' => $order->invoice_number])),
                    copy(text) {
                        navigator.clipboard.writeText(text)
                            .then(() => {
                                $.store('notifiers').toast({
                                    variant: 'success',
                                    title: 'Success',
                                    message: 'Copied to clipboard!'
                                });
                            })
                            .catch(err => {
                                console.error('Failed to copy:', err);
                            });
                    }
                }" class="mt-6 bg-white shadow-sm px-4 py-2 rounded-lg flex justify-between items-center">
                    <pre x-text="url" class="line-clamp-1"></pre>
                    <button x-on:click="copy(url)">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                        </svg>
                    </button>
                </div>

                <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                    Only share this link with someone you trust. During shipping, anyone with this link can view your order’s location.
                </p>

                <div class="mt-8 flex items-center gap-4 text-center justify-center">
                    <a class="inline-block rounded-md border border-transparent bg-yellow-500 px-6 py-3 text-base font-medium text-white shadow-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 "
                       href="{{ route('welcome') }}">
                        Continue Shopping
                    </a>
                    <a class="inline-block rounded-md border border-transparent bg-[#E53935] px-6 py-3 text-base font-medium text-white shadow-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-[#E53935] focus:ring-offset-2 "
                       href="{{ route('welcome') }}">
                        Live View
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-toast />
</x-app>
