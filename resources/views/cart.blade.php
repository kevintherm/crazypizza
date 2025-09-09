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
                        shipping: 0,
                        tax: 0,
                        cartTotal: 0,

                        cartItems: @js($cart->items),
                        availableToppings: @js(Ingredient::where('available_as_topping', true)->get(['id', 'name', 'price_per_unit'])),
                        appliedCoupon: null,
                        couponDiscount: 0,
                        updateItem() {
                            axios.post(@js(route('cart.update')), {
                                    items: this.cartItems,
                                    coupon: this.appliedCoupon
                                })
                                .then(res => {
                                    const data = res.data.data;

                                    this.shipping = data.shipping;
                                    this.tax = data.tax;
                                    this.subtotal = data.subtotal;
                                    this.couponDiscount = data.discount;
                                    this.cartTotal = data.total;
                                })
                                .catch(err => {
                                    $.store('notifiers').toast({
                                        variant: err.response?.status == 400 ? 'warning' : 'danger',
                                        title: 'Oops...',
                                        message: err.response?.data?.message || 'Something went wrong.'
                                    });
                                });
                        },
                        removeItem(itemId) {
                            axios.post(@js(route('cart.remove')), {
                                item_id: itemId
                            }).then(res => {
                                this.cartItems = this.cartItems.filter(item => item.id !== itemId);
                                this.reloadItems();
                                $.store('notifiers').toast({
                                    variant: 'success',
                                    title: 'Success!',
                                    message: 'Item removed from cart.'
                                });
                            }).catch(err => {
                                $.store('notifiers').toast({
                                    variant: 'danger',
                                    title: 'Oops...',
                                    message: 'Something went wrong.'
                                });
                            });
                        },
                        reloadItems() {
                            if (this.cartItems.length > 0) {
                                this.updateItem();
                            } else {
                                // If cart is empty, reset all values
                                this.subtotal = 0;
                                this.shipping = 0;
                                this.tax = 0;
                                this.cartTotal = 0;
                                this.couponDiscount = 0;
                            }
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

                <div x-show="$store.mg?.cartItems.length < 1" class="text-center">
                    <p class="text-xl font-semibold">Your cart is empty. Choose something good to eat first.</p>
                    <a class="text-red-400 hover:underline" href="{{ route('pizzas') }}">Browse Pizzas</a>
                </div>

                <div x-show="$store.mg?.cartItems.length > 0" class="grid grid-cols-12 gap-y-8 md:gap-8 items-start">

                    <div class="col-span-12 md:col-span-8 flex flex-col gap-4">

                        <template x-for="(item, i) in $store.mg?.cartItems" :key="item.id + item.pizza.id">
                            <div class="p-4 rounded-lg shadow-md bg-white">
                                <div class="flex flex-col-reverse md:flex-row items-start justify-between">
                                    <div class="flex-grow">
                                        <div class="flex items-baseline justify-between">
                                            <div>
                                                <h3 x-text="item.pizza.name" class="text-2xl text-black font-bold"></h3>
                                                <p x-text="money(item.pizza.price)" class="text-lg"></p>
                                            </div>
                                            <button class="text-red-500 hover:text-red-700" @click="$store.mg.removeItem(item.id)">
                                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>


                                        <div class="w-full h-3"></div>

                                        <div class="flex justify-between md:justify-start items-center gap-2">
                                            <label class="text-lg pe-2" for="quantity">Quantity</label>
                                            <div x-data="{ minVal: 1, maxVal: 100, decimalPoints: 0, incrementAmount: 1 }" class="flex items-center">
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
                                            <label class="text-lg pe-2" :for="'item-' + item.id">Size</label>
                                            <select x-model="item.size" x-on:change="$store.mg.updateItem(item.id)" class="text-lg text-black pe-2" :id="'item-' + item.id">
                                                @foreach (Pizza::SIZE as $i => $size)
                                                    <option value="{{ $i }}">{{ ucfirst($size) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-full h-3"></div>

                                        <div x-data="{
                                            showToppings: item.ingredients?.length > 0,
                                            selectedToppings: item.ingredients || [],
                                            debouncedUpdateItem: $.debounce(function() {
                                                const storeItem = $store.mg.cartItems.find(cartItem => cartItem.id === item.id);
                                                if (storeItem) {
                                                    storeItem.ingredients = this.selectedToppings;
                                                }

                                                $store.mg.updateItem(item.id);
                                            }, 500),
                                            isToppingSelected(toppingId) {
                                                return this.selectedToppings.some(t => t.id === toppingId);
                                            },
                                            getToppingQuantity(toppingId) {
                                                const topping = this.selectedToppings.find(t => t.id === toppingId);
                                                return topping ? topping.quantity : 0;
                                            },
                                            toggleTopping(topping) {
                                                const index = this.selectedToppings.findIndex(t => t.id === topping.id);
                                                if (index === -1) {
                                                    this.selectedToppings.push({ ...topping, quantity: 1 });
                                                } else {
                                                    this.selectedToppings.splice(index, 1);
                                                }
                                                this.debouncedUpdateItem();
                                            },
                                            updateToppingQuantity(toppingId, newQuantity) {
                                                const index = this.selectedToppings.findIndex(t => t.id === toppingId);
                                                if (index !== -1) {
                                                    if (newQuantity > 0) {
                                                        this.selectedToppings[index].quantity = parseInt(newQuantity);
                                                    } else {
                                                        this.selectedToppings.splice(index, 1);
                                                    }
                                                    this.debouncedUpdateItem();
                                                }
                                            }
                                        }">
                                            <button class="text-md text-black flex items-center gap-2" @click="showToppings = !showToppings">
                                                Add Toppings
                                                <svg class="size-5 duration-100" :class="{ 'rotate-180': showToppings }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                            <div x-show="showToppings" x-collapse>
                                                <div class="grid grid-cols-2 gap-2 mt-2">
                                                    <template x-for="topping in $store.mg.availableToppings" :key="topping.id">
                                                        <div class="p-3 rounded-2xl border border-zinc-300">
                                                            <div class="flex flex-col gap-2">
                                                                <label class="flex items-center gap-2 cursor-pointer">
                                                                    <span class="relative flex items-center">
                                                                        <input class="before:content[''] peer relative size-4 appearance-none overflow-hidden rounded border border-neutral-300 bg-neutral-50 before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 disabled:cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white"
                                                                               type="checkbox" :value="topping.id" @change="toggleTopping(topping)" :checked="isToppingSelected(topping.id)" />
                                                                        <svg class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black"
                                                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                        </svg>
                                                                    </span>
                                                                    <span x-text="topping.name" class="line-clamp-2"></span>
                                                                </label>
                                                                <span x-text="money(topping.price_per_unit)" class="text-sm text-gray-500"></span>
                                                            </div>
                                                            <div x-show="isToppingSelected(topping.id)" class="flex items-center gap-2 mt-2">
                                                                <label class="text-sm" :for="'topping-qty-' + item.id + '-' + topping.id">Qty:</label>
                                                                <input class="w-full md:w-16 text-lg text-center border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                                                                       :id="'topping-qty-' + item.id + '-' + topping.id" type="number" min="1" max="20" :value="getToppingQuantity(topping.id)"
                                                                       @input="updateToppingQuantity(topping.id, $event.target.value)">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <img class="w-full md:w-1/4 aspect-square object-cover rounded-2xl mb-6 md:ml-4" :src="item.pizza.image" alt="Pizza" draggable="false">
                                </div>
                            </div>
                        </template>

                    </div>

                    <div class="col-span-12 md:col-span-4 rounded-2xl p-4 bg-white shadow-lg">

                        <h2 class="text-2xl font-bold leading-tight text-center text-black py-4 border-b border-zinc-300">Order Summary</h2>

                        <div class="py-2 flex justify-between items-center">
                            <p class="text-lg font-semibold leading-tight">Subtotal</p>
                            <p x-text="money($store.mg?.subtotal)" class="text-lg font-semibold leading-tight text-black"></p>
                        </div>

                        <div class="py-2 flex justify-between items-center">
                            <p class="text-lg font-semibold leading-tight">Shipping</p>
                            <p x-text="money($store.mg?.shipping)" class="text-lg font-semibold leading-tight text-black"></p>
                        </div>

                        <div class="py-2 flex justify-between items-center border-b border-zinc-300">
                            <p class="text-lg font-semibold leading-tight">Tax</p>
                            <p x-text="money($store.mg?.tax)" class="text-lg font-semibold leading-tight text-black">FREE</p>
                        </div>

                        <div class="py-2 flex justify-between items-center">
                            <p class="text-lg font-semibold leading-tight">Coupon</p>
                            <div class="relative">
                                <input x-on:input.debounce.500ms="$store.mg.appliedCoupon = $el.value; $store.mg.reloadItems()" id="coupon" name="coupon"
                                       class="w-full rounded-2xl border border-neutral-300 bg-neutral-50 px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white"
                                       type="text" autocomplete="off" placeholder="Enter your coupon" />
                            </div>
                        </div>

                        <div x-show="$store.mg?.appliedCoupon" x-cloak class="py-2 flex justify-between items-center text-green-600">
                            <p class="text-lg font-semibold leading-tight">Discount</p>
                            <p x-text="`- ${money($store.mg?.couponDiscount)}`" class="text-lg font-semibold leading-tight"></p>
                        </div>

                        <div class="py-4 flex justify-between items-center border-t border-zinc-300">
                            <p class="text-xl font-bold leading-tight">TOTAL</p>
                            <p x-text="money($store.mg?.cartTotal)" class="text-xl font-bold leading-tight text-black"></p>
                        </div>

                        <div class="pt-2">
                            <button class="w-full whitespace-nowrap rounded-2xl bg-black border border-black px-4 py-2 text-lg font-medium tracking-wide text-neutral-100 transition hover:bg-gray-800 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-white dark:border-white dark:text-black dark:focus-visible:outline-white"
                                    type="button">Checkout</button>
                        </div>

                    </div>

                </div>

            </section>

        </div>

    </main>

    <x-footer />

</x-app>
