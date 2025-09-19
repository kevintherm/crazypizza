<x-app :title="'Order ' . $order->invoice">

    <x-slot name="foot">
        <x-toast :duration="5000" />

        <script>
            window.addEventListener('alpine:init', () => {
                document.addEventListener('DOMContentLoaded', () => {
                    @if (session('error'))
                        $.store('notifiers').toast({
                            variant: 'danger',
                            title: 'Oops...',
                            message: @js(session('error'))
                        });
                    @enderror

                    @if (session('success'))
                        $.store('notifiers').toast({
                            variant: 'success',
                            title: 'Success',
                            message: @js(session('success'))
                        });
                    @enderror
                })
            });
        </script>
    </x-slot>

    <div>
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
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-900 dark:text-white">Coupon Code:</dt>
                                <dd>
                                    <span class="px-4 py-0.5 rounded-radius border border-outline dark:border-outline-dark bg-surface/50 dark:bg-surface-dark/50 uppercase">
                                        {{ $order->coupon_code }}
                                    </span>
                                </dd>
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
                            <div class="flex justify-between">
                                <dt class="font-bold text-gray-900 dark:text-white">Coupon:</dt>
                                <dd>-{{ (new \App\ValueObjects\Money($order->coupon->discount))->format() }}</dd>
                            </div>
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
                }" class="mt-10 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 flex items-center justify-between px-4 py-2">
                    <pre x-text="url" class="line-clamp-1 text-zinc-700 dark:text-zinc-300"></pre>
                    <button x-on:click="copy(url)">
                        <svg class="size-4 text-zinc-700 dark:text-zinc-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                    @if ($order->status == \App\Models\Order::STATUS['paid'])
                        <a class="inline-block rounded-md border border-transparent bg-[#E53935] px-6 py-3 text-base font-medium text-white shadow-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-[#E53935] focus:ring-offset-2 "
                           href="javascript:alert('soon')">
                            Live View
                        </a>
                    @endif
                </div>

                {{-- RATING SYSTEM START --}}
                @if ($order->status == \App\Models\Order::STATUS['completed'])
                    <div class="mt-10 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Rate The Craziness</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">We'd love to hear your feedback on your order!</p>
                        </div>

                        <form action="{{ !$order->reviewed ? route('order.rate', ['invoice' => $order->invoice_number]) : '#' }}" method="POST">
                            @csrf

                            {{-- The fieldset will disable all child form elements if the 'disabled' attribute is present. --}}
                            <fieldset @if ($order->reviewed) disabled @endif>

                                {{-- Add a message if the order has already been reviewed --}}
                                @if ($order->reviewed)
                                    <div class="p-6 text-center bg-green-50 dark:bg-green-900/50">
                                        <p class="text-sm font-medium text-green-800 dark:text-green-300">You have already reviewed this order. Thank you for your feedback!</p>
                                    </div>
                                @endif

                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($order->json['cart_items'] as $item)
                                        @php
                                            // Find the review corresponding to the current pizza item from the 'reviews' relationship
                                            $review = $order->reviews->firstWhere('pizza_id', $item['pizza_id']);
                                        @endphp
                                        {{-- Pre-populate rating and comment if a review exists --}}
                                        <div x-data="{ rating: {{ $review->rating ?? 0 }}, hoverRating: 0 }" class="space-y-4 p-6">
                                            <div class="flex items-center space-x-4">
                                                <img class="h-16 w-16 rounded-md object-cover" src="{{ $item['pizza']['image'] ?? 'https://placehold.co/400' }}" alt="{{ $item['pizza']['name'] }}">
                                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $item['pizza']['name'] }}</h3>
                                            </div>

                                            <div>
                                                <div class="flex items-center">
                                                    <input x-model="rating" name="ratings[{{ $item['pizza_id'] }}][rating]" type="hidden" required>
                                                    <template x-for="star in 5" :key="star">
                                                        <button class="text-gray-300 transition-colors dark:text-gray-500" type="button"
                                                                @if (!$order->reviewed) @click="rating = star"
                                            @mouseover="hoverRating = star"
                                            @mouseleave="hoverRating = 0" @endif
                                                                :class="{ '!text-amber-500': hoverRating >= star || rating >= star }">
                                                            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                                <div class="mt-4">
                                                    <label class="sr-only" for="comment-{{ $item['pizza_id'] }}">Comment for {{ $item['pizza']['name'] }}</label>
                                                    <textarea x-grow id="comment-{{ $item['pizza_id'] }}" name="ratings[{{ $item['pizza_id'] }}][comment]"
                                                              class="focus:border-0 focus:outline-0 focus-within:border block w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white sm:text-sm" rows="3"
                                                              placeholder="Tell us more... (optional)">{{ $review->comment ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (!$order->reviewed)
                                    <div class="bg-gray-50 p-6 dark:bg-gray-800/50">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="email">Verification Email</label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Please enter the email you used to place the order.</p>
                                            <div class="mt-1">
                                                <input id="email" name="email"
                                                       class="focus:border-0 focus:outline-0 focus-within:border block w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white sm:text-sm"
                                                       type="email" placeholder="you@example.com" autocomplete="email" required>
                                            </div>
                                        </div>

                                        <button class="mt-6 w-full rounded-md border border-transparent bg-yellow-500 px-6 py-3 text-base font-medium text-white shadow-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                type="submit">
                                            {{-- Change button text based on review status --}}
                                            {{ $order->reviewed ? 'Already Submitted' : 'Submit Reviews' }}
                                        </button>
                                    </div>
                                @endif
                            </fieldset>
                        </form>

                    </div>
                @endif
                {{-- RATING SYSTEM END --}}
            </div>
        </div>
    </div>

    <x-toast />
</x-app>
