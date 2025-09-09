@use('\App\Models\{Ingredient,Pizza}')

<x-app title="Cart">

    <x-slot name="head">
        <style>
            .font-pacifico {
                font-family: 'Pacifico', cursive;
            }

            .text-stroke {
                -webkit-text-stroke: 2px #fff;
                color: transparent;
            }

            * {
                transition: all 500ms;
            }
        </style>
    </x-slot>

    <x-slot name="foot">
        <x-toast />

        <script>
            window.addEventListener('alpine:init', () => {
                document.addEventListener('DOMContentLoaded', () => {
                    $.store('mg', {
                        subtotal: 0,
                        cartTotal: 0,
                        cartItems: @js($cart->items),
                        availableToppings: @js(Ingredient::where('available_as_topping', true)->get(['id', 'name', 'price_per_unit'])),
                        appliedCoupon: null,
                        updateItem(itemId) {
                            axios.post(@js(route('cart.update')), {
                                    items: this.cartItems,
                                })
                                .then(res => {
                                    this.subtotal = res.data.data.subtotal;
                                })
                                .catch(err => {
                                    $.store('notifiers').toast({
                                        variant: err.response?.status == 400 ? 'warning' : 'danger',
                                        title: 'Oops...',
                                        message: err.response?.data?.message || 'Something went wrong.'
                                    });
                                });
                        },
                        reloadItems() {
                            this.updateItem(this.cartItems[0]);
                        },
                        init() {
                            this.reloadItems();
                        }
                    });
                })
            });
        </script>
    </x-slot>

    <x-navbar />

    <main x-init="$store.prefs.setTheme('light')" x-init="$watch('$store.prefs.state.theme', () => $store.prefs.setTheme('light'))" id="main">

        <div class="flex flex-col min-h-[25vh] relative overflow-hidden">

            <img class="w-full h-[50vh] absolute inset-0 object-cover" src="{{ asset('assets/images/sssquiggly.svg') }}">

            <div class="bg-[#E53935] py-6 px-8 md:px-24 ">

                <img class="absolute top-0 right-0 translate-x-1/3 md:translate-x-1/2 md:-translate-y-1/2 w-1/2 aspect-square object-contain" src="{{ asset('assets/images/pizza.jpg') }}" alt="Crazy Pizza" draggable="false">

                <div class="max-w-xl md:ml-24 mt-24 mx-auto relative z-10">
                    <h1 class="text-2xl text-white md:text-3xl font-extrabold leading-tight ">
                        Cart
                    </h1>

                    <div class="w-full h-8"></div>

                    <p class="italic font-medium text-white">Modify your requests here.</p>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#E53935" fill-opacity="1"
                      d="M0,64L40,64C80,64,160,64,240,69.3C320,75,400,85,480,80C560,75,640,53,720,80C800,107,880,181,960,197.3C1040,213,1120,171,1200,160C1280,149,1360,171,1400,181.3L1440,192L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
                </path>
            </svg>

            <section id="menu" class="max-w-6xl mx-auto px-8 py-6">

                <div x-show="$store.mg?.cartItems.length < 1">
                    <p class="text-xl font-semibold">Your cart is empty. Choose something good to eat first.</p>
                </div>

                <div x-show="$store.mg?.cartItems.length > 0" class="grid grid-cols-12 gap-y-8 md:gap-8 items-baseline">

                    <div class="col-span-12 md:col-span-8 flex flex-col gap-4">

                        <template x-for="(item, i) in $store.mg?.cartItems" :key="item.id + item.pizza.id">
                            <div x-data="{
                                showToppings: false,
                                selectedToppings: [],
                                debouncedUpdateItem: $.debounce((val) => {
                                    $store.mg.updateItem(item.id);
                                }, 500),
                                addOrUpdate(topping) {
                                    const index = this.selectedToppings.findIndex(top => top.id == topping.id);
                                    if (index == -1) {
                                        const toInsert = { ...topping, quantity: 1 };
                                        this.selectedToppings = [toInsert, ...this.selectedToppings];
                                    } else {
                                        const toUpdate = this.selectedToppings[index];
                                        toUpdate.quantity++;
                                    }

                                    item.ingredients = this.selectedToppings.map(e => ({ id: e.id, quantity: e.quantity }));;
                                    this.debouncedUpdateItem();
                                },
                                remove(topping) {
                                    this.selectedToppings = this.selectedToppings.filter(t => t.id != topping.id);
                                    $store.mg.updateItem(item.id);
                                }
                            }" class="border-b border-zinc-500 px-4 py-2">

                                <div class="flex flex-col-reverse md:flex-row items-start justify-between">
                                    <div>
                                        <h3 x-text="item.pizza.name" class="text-3xl text-black font-bold"></h3>
                                        <p x-text="money(item.pizza.price)" class="text-xl"></p>

                                        <div class="w-full h-3"></div>

                                        <div x-data="{ minVal: 1, maxVal: 100, decimalPoints: 0, incrementAmount: 1 }" class="flex justify-between md:justify-start items-center gap-2">
                                            <label class="text-xl md:border-e pe-2" for="quantity">Quantity</label>
                                            <div x-on:dblclick.prevent class="flex items-center">
                                                <button x-on:click="item.quantity = Math.max(minVal, item.quantity - incrementAmount);" x-on:click.debounce.500ms="$store.mg.updateItem(item.id)"
                                                        class="flex h-10 items-center justify-center rounded-l-2xl border border-neutral-300 bg-neutral-50 px-4 py-2 text-neutral-600 hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-visible:outline-white"
                                                        aria-label="subtract">
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                    </svg>
                                                </button>
                                                <input x-model="item.quantity" id="quantity"
                                                       class="border-x-none h-10 w-full md:w-20 rounded-none border-y border-neutral-300 bg-neutral-50/50 text-center text-neutral-900 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-black dark:border-neutral-700 dark:bg-neutral-900/50 dark:text-white dark:focus-visible:outline-white"
                                                       type="text" readonly />
                                                <button x-on:click="item.quantity = Math.min(maxVal, item.quantity + incrementAmount);" x-on:click.debounce.500ms="$store.mg.updateItem(item.id)"
                                                        class="flex h-10 items-center justify-center rounded-r-2xl border border-neutral-300 bg-neutral-50 px-4 py-2 text-neutral-600 hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-visible:outline-white"
                                                        aria-label="add">
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="w-full h-3"></div>

                                        <div class="flex justify-between md:justify-start items-center gap-2">
                                            <label class="text-lg md:border-e pe-2" :for="'item-' + item.id">Size</label>
                                            <select x-model="item.size" x-on:change="$store.mg.updateItem(item.id)" class="text-xl text-black pe-2" :id="'item-' + item.id">
                                                @foreach (Pizza::SIZE as $i => $size)
                                                    <option value="{{ $i }}">{{ ucfirst($size) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-full h-3"></div>

                                        <div class="flex justify-between md:justify-start items-center gap-2">
                                            <p class="text-xl md:border-e pe-2" for="size">Toppings</p>
                                            <p x-text="selectedToppings < 1 ? 'Nothing' : selectedToppings.length + ' Items'" class="text-xl text-black pe-2">Nothing</p>
                                        </div>

                                        <div class="flex flex-col gap-1 ms-4">
                                            <template x-for="(topping, index) in selectedToppings" :key="topping.id">
                                                <div class="flex items-center gap-1">
                                                    <button x-on:click="remove(topping)">
                                                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                    <p x-text="topping.name" class="text-xl pe-2"></p>
                                                    <p x-text="topping.quantity + 'x'" class="text-black"></p>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="w-full h-3"></div>

                                        <button x-on:click="showToppings = !showToppings" class="text-md text-black flex items-center gap-2">
                                            Add Toppings
                                            <svg class="size-5 duration-100" :class="{ 'rotate-180': showToppings }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>

                                        <div class="w-full h-1"></div>

                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(topping, index) in $store.mg?.availableToppings" :key="topping.id">
                                                <button x-transition x-cloak x-show="showToppings" x-on:click="addOrUpdate(topping)"
                                                        class="w-fit inline-flex overflow-hidden rounded-2xl border border-neutral-300 bg-white text-xs font-medium text-neutral-600 hover:border-neutral-600" type="button">
                                                    <span class="flex items-center gap-1 bg-neutral-50/10 px-2 py-1 dark:bg-neutral-900/10">
                                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                        </svg>
                                                        <span x-text="topping.name"></span>
                                                        <span class="size-1 rounded-full bg-neutral-600 dark:bg-neutral-300"></span>
                                                        <span x-text="topping.price_per_unit"></span>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>

                                    </div>
                                    <img class="w-full md:w-1/5 aspect-square object-cover rounded-2xl mb-6" src="https://placehold.co/400" alt="Pizza" draggable="false">
                                </div>

                            </div>
                        </template>

                    </div>

                    <div class="col-span-12 md:col-span-4 border border-zinc-600 rounded-2xl">

                        <h2 class="text-3xl font-bold leading-tight text-center text-black py-4">Order Summary</h2>

                        <div class="w-full">
                            <hr>
                        </div>

                        <div class="px-4 py-2 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">Subtotal</p>
                            <p x-text="money($store.mg?.subtotal)" class="text-xl font-semibold leading-tight text-black"></p>
                        </div>

                        <div class="px-4 py-2 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">Shipping</p>
                            <p class="text-xl font-semibold leading-tight text-black">FREE</p>
                        </div>

                        <div class="px-4 py-2 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">Tax</p>
                            <p class="text-xl font-semibold leading-tight text-black">FREE</p>
                        </div>

                        <div class="w-full">
                            <hr>
                        </div>

                        <div class="px-4 py-2 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">Coupon</p>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input id="coupon" name="coupon"
                                       class="w-full rounded-2xl border border-neutral-300 bg-neutral-50 px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white"
                                       type="text" autocomplete="current-password" placeholder="Enter your coupon" />
                                <button x-on:click="showPassword = !showPassword" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-neutral-600 dark:text-neutral-300" type="button" aria-label="Show password">
                                    <svg x-show="!showPassword" class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="showPassword" class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="px-4 py-2 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">Discount</p>
                            <p class="text-xl font-semibold leading-tight text-black">-$50.00</p>
                        </div>

                        <div class="w-full">
                            <hr>
                        </div>

                        <div class="px-4 py-4 flex justify-between items-center">
                            <p class="text-xl font-semibold leading-tight">TOTAL</p>
                            <p x-text="money($store.mg?.cartTotal)" class="text-xl font-semibold leading-tight text-black"></p>
                        </div>

                        <div class="p-4 pt-0">
                            <button class="w-full whitespace-nowrap rounded-2xl bg-black border border-black px-4 py-2 text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-white dark:border-white dark:text-black dark:focus-visible:outline-white"
                                    type="button">Pay</button>
                        </div>

                    </div>

                </div>

            </section>

        </div>

    </main>

    <x-footer />

</x-app>
