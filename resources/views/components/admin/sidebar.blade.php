<div x-data="{
    currentRoute: window.location.pathname
}" class="flex flex-col gap-2 h-full pb-6">
    <!-- Dashboard -->
    <a href="/dashboard"
        x-bind:class="currentRoute === '/dashboard' ?
            'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus-visible:underline focus:outline-hidden' :
            'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0"
            aria-hidden="true">
            <path
                d="M15.5 2A1.5 1.5 0 0 0 14 3.5v13a1.5 1.5 0 0 0 1.5 1.5h1a1.5 1.5 0 0 0 1.5-1.5v-13A1.5 1.5 0 0 0 16.5 2h-1ZM9.5 6A1.5 1.5 0 0 0 8 7.5v9A1.5 1.5 0 0 0 9.5 18h1a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 10.5 6h-1ZM3.5 10A1.5 1.5 0 0 0 2 11.5v5A1.5 1.5 0 0 0 3.5 18h1A1.5 1.5 0 0 0 6 16.5v-5A1.5 1.5 0 0 0 4.5 10h-1Z" />
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Products Collapsible -->
    <div x-data="{ isExpanded: currentRoute.includes('/products') }" class="flex flex-col">
        <button type="button" x-on:click="isExpanded = !isExpanded" id="products-btn" aria-controls="products"
            x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
            class="flex items-center justify-between rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus:outline-hidden focus-visible:underline"
            x-bind:class="isExpanded || currentRoute.includes('/products') ?
                'text-on-surface-strong bg-primary/10 dark:text-on-surface-dark-strong dark:bg-primary-dark/10' :
                'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0"
                aria-hidden="true">
                <path
                    d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 4v8.25l6.862-3.786A.75.75 0 0 0 18 14.25V6.443ZM9.25 18.693v-8.25l-7.25-4v7.807a.75.75 0 0 0 .388.657l6.862 3.786Z" />
            </svg>
            <span class="mr-auto text-left">Products</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-5 transition-transform rotate-0 shrink-0"
                x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <ul x-cloak x-collapse x-show="isExpanded" aria-labelledby="products-btn" id="products">
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/products/ingredients"
                    x-bind:class="currentRoute === '/products/ingredients' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    Ingredients
                </a>
            </li>
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/products/inventory"
                    x-bind:class="currentRoute === '/products/inventory' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    Inventory
                </a>
            </li>
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/products/reviews"
                    x-bind:class="currentRoute === '/products/reviews' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    Reviews
                </a>
            </li>
        </ul>
    </div>

    <!-- Orders Collapsible -->
    <div x-data="{ isExpanded: currentRoute.includes('/orders') }" class="flex flex-col">
        <button type="button" x-on:click="isExpanded = !isExpanded" id="orders-btn" aria-controls="orders"
            x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
            class="flex items-center justify-between rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus:outline-hidden focus-visible:underline"
            x-bind:class="isExpanded || currentRoute.includes('/orders') ?
                'text-on-surface-strong bg-primary/10 dark:text-on-surface-dark-strong dark:bg-primary-dark/10' :
                'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0"
                aria-hidden="true">
                <path
                    d="M6.5 3c-1.051 0-2.093.04-3.125.117A1.49 1.49 0 0 0 2 4.607V10.5h9V4.606c0-.771-.59-1.43-1.375-1.489A41.568 41.568 0 0 0 6.5 3ZM2 12v2.5A1.5 1.5 0 0 0 3.5 16h.041a3 3 0 0 1 5.918 0h.791a.75.75 0 0 0 .75-.75V12H2Z" />
                <path
                    d="M6.5 18a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM13.25 5a.75.75 0 0 0-.75.75v8.514a3.001 3.001 0 0 1 4.893 1.44c.37-.275.61-.719.595-1.227a24.905 24.905 0 0 0-1.784-8.549A1.486 1.486 0 0 0 14.823 5H13.25ZM14.5 18a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
            </svg>
            <span class="mr-auto text-left">Orders</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-5 transition-transform rotate-0 shrink-0"
                x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <ul x-cloak x-collapse x-show="isExpanded" aria-labelledby="orders-btn" id="orders">
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/orders/pending"
                    x-bind:class="currentRoute === '/orders/pending' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    <span>Pending</span>
                    <span class="ml-auto font-bold">3</span>
                </a>
            </li>
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/orders/shipped"
                    x-bind:class="currentRoute === '/orders/shipped' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    <span>Shipped</span>
                    <span class="ml-auto font-bold">12</span>
                </a>
            </li>
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/orders/completed"
                    x-bind:class="currentRoute === '/orders/completed' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    <span>Completed</span>
                    <span class="ml-auto font-bold">38</span>
                </a>
            </li>
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/orders/returns"
                    x-bind:class="currentRoute === '/orders/returns' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    <span>Returns</span>
                    <span class="ml-auto font-bold">2</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Settings -->
    <a href="/settings"
        x-bind:class="currentRoute === '/settings' ?
            'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus-visible:underline focus:outline-hidden' :
            'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0"
            aria-hidden="true">
            <path fill-rule="evenodd"
                d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                clip-rule="evenodd" />
        </svg>
        <span>Settings</span>
    </a>

    <div x-data="{ isExpanded: currentRoute.includes('/orders') }" class="flex flex-col mt-auto">
        <button type="button" x-on:click="isExpanded = !isExpanded" id="orders-btn" aria-controls="orders"
            x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
            class="flex items-center justify-between rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus:outline-hidden focus-visible:underline"
            x-bind:class="isExpanded || currentRoute.includes('/orders') ?
                'text-on-surface-strong bg-primary/10 dark:text-on-surface-dark-strong dark:bg-primary-dark/10' :
                'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5'">
            <span
                class="flex size-6 items-center justify-center overflow-hidden rounded-full border border-outline bg-surface-alt text-on-surface/50 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark/50">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"
                    class="w-full h-full mt-3">
                    <path fill-rule="evenodd"
                        d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <span class="mr-auto text-left">{{ Auth::user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-5 transition-transform rotate-0 shrink-0"
                x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <ul x-cloak x-collapse x-show="isExpanded" aria-labelledby="orders-btn" id="orders">
            <li class="px-1 py-0.5 first:mt-2">
                <a href="/profile"
                    x-bind:class="currentRoute === '/profile' ?
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                    <span>Profile</span>
                </a>
            </li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <li class="px-1 py-0.5 first:mt-2">
                    <button type="submit"
                        x-bind:class="currentRoute === '/logout' ?
                            'w-full flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface-strong bg-primary/5 dark:text-on-surface-dark-strong dark:bg-primary-dark/5 underline-offset-2 focus:outline-hidden focus-visible:underline' :
                            'w-full flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus:outline-hidden focus-visible:underline dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong'">
                        <span>Logout</span>
                    </button>
                </li>
            </form>
        </ul>
    </div>


</div>
