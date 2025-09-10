<x-app>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-xl">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Payment Canceled
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        Your payment was not completed. Your cart has been saved.
                    </p>
                </div>

                <div class="mt-10 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            If you'd like to try again, you can return to your cart.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('cart') }}"
                       class="inline-block rounded-md border border-transparent bg-yellow-500 px-6 py-3 text-base font-medium text-black shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Return to Cart
                    </a>
                    <a href="{{ route('welcome') }}"
                       class="inline-block rounded-md border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app>
