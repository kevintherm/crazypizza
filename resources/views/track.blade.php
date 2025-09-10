<x-app :title="'Track Order'">

    @if (session('error'))
        <x-slot name="foot">
            <x-toast :duration="5000" />

            <script>
                window.addEventListener('alpine:init', () => {
                    document.addEventListener('DOMContentLoaded', () => {
                        $.store('notifiers').toast({
                            variant: 'danger',
                            title: 'Oops...',
                            message: @js(session('error'))
                        });
                    })
                });
            </script>
        </x-slot>
    @enderror

    <div class="invert">
        <x-navbar />
    </div>

    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-32 sm:px-6 lg:px-8">
            <form class="mx-auto max-w-xl" action="" method="POST">
                <div class="text-center">

                    <svg class="mx-auto h-16 w-16 text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Track your order here!
                    </h1>
                </div>

                <div class="mt-10 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="p-6">
                        <div class="relative flex w-full flex-col gap-1 text-neutral-600 dark:text-neutral-300">
                            <svg class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-neutral-600/50 dark:text-neutral-300/50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <input name="invoice"
                                   class="w-full rounded-2xl border border-neutral-300 bg-neutral-50 py-2 pl-10 pr-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white text-xl"
                                   type="invoice" placeholder="Enter invoice" aria-label="invoice" />
                        </div>

                        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                            You can use your invoice number to track your order. If you have any questions, please contact our support team.
                        </p>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-4 text-center justify-center">
                    @csrf

                    <button class="inline-block rounded-md border border-transparent bg-yellow-500 px-6 py-3 text-base font-medium text-white shadow-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 "
                            type="submit">
                        Track
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
