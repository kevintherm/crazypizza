<x-app title="">

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

    <nav x-init="window.addEventListener('scroll', () => {
        scrolled = window.scrollY > 0
    })" x-data="{ mobileMenuIsOpen: false, scrolled: false }" x-on:click.away="mobileMenuIsOpen = false" class=" z-50 fixed top-0 w-full" aria-label="navbar">
        <div class="flex items-center justify-between px-8 py-6 rounded-b-2xl md:rounded-full md:m-4 transition-all duration-500" :class="scrolled ? 'bg-[#E53935]/85 backdrop-blur-sm' : 'bg-transparent'">
            <a class="flex items-center" href="/">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M8.18092 2.56556C7.90392 3.05195 7.65396 3.65447 7.416 4.36507C5.57795 9.34447 2.73476 16.6246 1.36225 20.12C0.73894 21.7073 2.25721 23.2963 3.87117 22.7465C7.38796 21.5484 14.6626 19.0869 19.6353 17.5194L19.6504 17.5145C20.3639 17.277 20.9659 17.0333 21.4491 16.7641C21.9273 16.4977 22.3551 16.1704 22.6426 15.7347C23.2987 14.7406 22.9351 13.6998 22.5012 12.8954C19.7712 7.83439 16.3585 4.2775 12.0968 1.5703C11.6898 1.31179 11.2341 1.09226 10.7418 1.02286C10.2141 0.948472 9.69595 1.05467 9.22968 1.36307C8.79315 1.65181 8.45686 2.08103 8.18092 2.56556ZM15.0912 9.09151C13.5105 7.4048 11.7893 5.97947 9.55526 4.3325C9.6817 4.01505 9.80284 3.75901 9.91885 3.55532C10.1115 3.21703 10.2575 3.08115 10.333 3.03119C10.3788 3.0009 10.4025 2.99481 10.4626 3.00327C10.5579 3.01672 10.7358 3.07517 11.0244 3.25848C14.994 5.78016 18.1714 9.08132 20.741 13.8449C21.0989 14.5085 20.9833 14.6233 20.9739 14.6325L20.9734 14.6331C20.9318 14.696 20.8089 14.8313 20.4757 15.017C20.2861 15.1226 20.0491 15.2333 19.7558 15.3501C18.0975 12.7134 16.6772 10.7839 15.0912 9.09151ZM13.6318 10.4591C15.0211 11.9415 16.2981 13.6452 17.8022 16.0033C12.9009 17.5716 6.46194 19.751 3.22621 20.8533L3.22459 20.8538L3.22391 20.8531L3.22329 20.8525L3.22387 20.851C4.48689 17.6345 7.00299 11.1934 8.83498 6.28876C10.7878 7.75003 12.2738 9.00998 13.6318 10.4591ZM10 13C11.1046 13 12 12.1046 12 11C12 9.89545 11.1046 9.00002 10 9.00002C8.89543 9.00002 8 9.89545 8 11C8 12.1046 8.89543 13 10 13ZM10 16C10 17.1046 9.10457 18 8 18C6.89543 18 6 17.1046 6 16C6 14.8954 6.89543 14 8 14C9.10457 14 10 14.8954 10 16ZM13 17C14.1046 17 15 16.1046 15 15C15 13.8954 14.1046 13 13 13C11.8954 13 11 13.8954 11 15C11 16.1046 11.8954 17 13 17Z"
                              fill="#ffffff"></path>
                    </g>
                </svg>
                <p class="text-xl text-white font-extrabold leading-tight">{{ config('app.name') }}</p>
            </a>
            <ul class="hidden items-center gap-4 md:flex">
                <li><a class="font-medium underline-offset-2 focus:outline-hidden focus:underline text-white" href="#">Pricing</a></li>
                <li><a class="font-medium underline-offset-2 focus:outline-hidden focus:underline text-white" href="#">Blog</a></li>
                <li><a class="font-medium underline-offset-2 focus:outline-hidden focus:underline text-white" href="/login">Login</a></li>
            </ul>
            <button x-on:click="mobileMenuIsOpen = !mobileMenuIsOpen" x-bind:aria-expanded="mobileMenuIsOpen" x-bind:class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" class="flex text-white md:hidden" type="button"
                    aria-label="mobile menu" aria-controls="mobileMenu">
                <svg x-cloak x-show="!mobileMenuIsOpen" class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-cloak x-show="mobileMenuIsOpen" class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <ul x-cloak x-show="mobileMenuIsOpen" x-transition:enter="transition motion-reduce:transition-none ease-out duration-300" x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition motion-reduce:transition-none ease-out duration-300" x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full" id="mobileMenu"
                class="fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-50 flex flex-col divide-y divide-zinc-300 rounded-b-2xl border-b border-zinc-300 bg-zinc-50 px-6 pb-6 pt-20 dark:divide-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 md:hidden">
                <li class="py-4"><a class="w-full text-lg font-medium text-zinc-800 focus:underline" href="#">Pricing</a></li>
                <li class="py-4"><a class="w-full text-lg font-medium text-zinc-800 focus:underline" href="#">Blog</a></li>
                <li class="py-4"><a class="w-full text-lg font-medium text-zinc-800 focus:underline" href="/login">Login</a></li>
            </ul>
        </div>
    </nav>

    <main x-init="$store.prefs.setTheme('light')" class="flex flex-col min-h-[80vh] relative overflow-hidden">

        <img class="w-full h-[120vh] absolute inset-0 object-cover" src="{{ asset('assets/images/sssquiggly.svg') }}">

        <div class="bg-[#E53935] py-6 px-8 md:px-24 ">

            <img class="absolute top-0 right-0 translate-x-1/3 md:translate-x-1/2 md:-translate-y-1/2 w-full aspect-square object-contain" src="{{ asset('assets/images/pizza.jpg') }}" alt="Crazy Pizza" draggable="false">

            <div class="max-w-xl md:ml-24 mt-24 mx-auto relative z-10">
                <h1 class="text-6xl text-white md:text-6xl lg:text-7xl font-extrabold leading-tight ">
                    Craziest
                    <span class="text-stroke block">pizza</span> that
                    will make you go <span class="text-stroke">CRAZY</span>!!
                </h1>

                <div class="w-full h-8"></div>

                <a class="whitespace-nowrap rounded-full bg-zinc-900 border border-zinc-900 px-6 py-3 text-lg font-medium tracking-wide text-neutral-100 transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-white dark:border-white dark:text-zinc-900 dark:focus-visible:outline-white"
                   href="/order" type="button">Get Crazy Now!</a>

                <div class="w-full h-8"></div>

                <p class="italic font-medium text-white">Exclusive coupon for your very first pizza!</p>
            </div>
        </div>


        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E53935" fill-opacity="1"
                  d="M0,64L40,64C80,64,160,64,240,69.3C320,75,400,85,480,80C560,75,640,53,720,80C800,107,880,181,960,197.3C1040,213,1120,171,1200,160C1280,149,1360,171,1400,181.3L1440,192L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
            </path>
        </svg>
    </main>

    <div class="w-full h-8"></div>

    <section class="max-w-3xl mx-auto px-8 py-6">
        <h1 class="font-pacifico text-5xl md:text-6xl text-zinc-800 text-center mb-16">Special Menu</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-12 place-items-center">

            @foreach (range(1, 4) as $i)
                <article
                         class="group flex rounded-2xl flex-col overflow-hidden border border-neutral-300 bg-neutral-50 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 hover:scale-105 transition duration-500 relative">

                    <div class="absolute top-0 left-0 right-0">
                        <div class="rounded-b-4xl w-full h-64 -translate-y-3/4 bg-red-400/20 group-hover:-translate-y-[10%] group-hover:bg-red-400/60 transition-all duration-700"></div>
                    </div>

                    <div class="max-w-1/2 mx-auto py-8 relative z-10">
                        <img class="object-cover transition duration-700 ease-out group-hover:rotate-180 rounded-full aspect-square" src="{{ asset('assets/images/menu1.jpg') }}" alt="CASIO G-SHOCK GA2100, Black face, black bands" />
                    </div>
                    <div class="flex flex-col gap-4 p-6">
                        <div class="flex flex-col md:flex-row gap-4 md:gap-12 justify-between">
                            <div class="flex flex-col">
                                <h3 class="text-lg lg:text-xl font-bold text-neutral-900 dark:text-white" aria-describedby="productDescription">CASIO G-SHOCK GA2100</h3>
                                <div class="flex items-center gap-1">
                                    <span class="sr-only">Rated 3 stars</span>
                                    @php($ratings = 4) {{-- /5 --}}
                                    @for ($i = 0; $i < $ratings; $i++)
                                        <svg class="size-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                    @for ($i = 0; $i < 5 - $ratings; $i++)
                                        <svg class="size-4 text-neutral-600/50 dark:text-neutral-300/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xl"><span class="sr-only">Price</span>$99.99</span>
                        </div>
                        <p id="productDescription" class="mb-2 text-pretty text-sm">
                            The Casio G-Shock GA2100 is simply designed for easy
                            timekeeping, featuring a sleek profile and clear display.
                        </p>
                        <button class="flex items-center justify-center gap-2 whitespace-nowrap bg-red-400 px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-bg-red-400 active:opacity-100 active:outline-offset-0 dark:bg-white dark:text-bg-red-400 dark:focus-visible:outline-white rounded-2xl"
                                type="button">
                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M5 4a3 3 0 0 1 6 0v1h.643a1.5 1.5 0 0 1 1.492 1.35l.7 7A1.5 1.5 0 0 1 12.342 15H3.657a1.5 1.5 0 0 1-1.492-1.65l.7-7A1.5 1.5 0 0 1 4.357 5H5V4Zm4.5 0v1h-3V4a1.5 1.5 0 0 1 3 0Zm-3 3.75a.75.75 0 0 0-1.5 0v1a3 3 0 1 0 6 0v-1a.75.75 0 0 0-1.5 0v1a1.5 1.5 0 1 1-3 0v-1Z"
                                      clip-rule="evenodd" />
                            </svg>
                            Order
                        </button>
                    </div>
                </article>
            @endforeach

        </div>

        <div class="w-full h-12"></div>

        <p class="font-pacifico text-zinc-700 text-lg text-center">Not finding your kind of pizza? <a class="text-red-400 hover:underline" href="/pizzas">More pizzas</a></p>

    </section>

    <div class="w-full h-16"></div>

    <section class="max-w-3xl mx-auto px-8 py-6">
        <h1 class="font-pacifico text-5xl md:text-6xl text-zinc-800 text-center mb-8">Gallery</h1>
        <p class="font-pacifico text-zinc-700 text-lg text-center mb-16">Glimpse of what our restaurant looks like</p>

        <div x-data="{
            slides: [{
                    imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                    imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',
                },
                {
                    imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                    imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',
                },
                {
                    imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                    imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',
                },
            ],
            currentSlideIndex: 1,
            previous() {
                if (this.currentSlideIndex > 1) {
                    this.currentSlideIndex = this.currentSlideIndex - 1
                } else {
                    // If it's the first slide, go to the last slide
                    this.currentSlideIndex = this.slides.length
                }
            },
            next() {
                if (this.currentSlideIndex < this.slides.length) {
                    this.currentSlideIndex = this.currentSlideIndex + 1
                } else {
                    // If it's the last slide, go to the first slide
                    this.currentSlideIndex = 1
                }
            },
        }" class="relative w-full overflow-hidden rounded-2xl">

            <!-- previous button -->
            <button x-on:click="previous()"
                    class="absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:outline-offset-0 dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white"
                    type="button" aria-label="previous slide">
                <svg class="size-5 md:size-6 pr-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- next button -->
            <button x-on:click="next()"
                    class="absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:outline-offset-0 dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white"
                    type="button" aria-label="next slide">
                <svg class="size-5 md:size-6 pl-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <!-- slides -->
            <!-- Change min-h-[50svh] to your preferred height size -->
            <div class="relative min-h-[50svh] w-full rounded-2xl">
                <template x-for="(slide, index) in slides">
                    <div x-show="currentSlideIndex == index + 1" x-transition.opacity.duration.1000ms class="absolute inset-0">
                        <img x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" class="absolute w-full h-full inset-0 object-cover text-neutral-600 dark:text-neutral-300 rounded-2xl" />
                    </div>
                </template>
            </div>

            <!-- indicators -->
            <div class="absolute rounded-2xl bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 bg-white/75 px-1.5 py-1 md:px-2 dark:bg-neutral-950/75" role="group" aria-label="slides">
                <template x-for="(slide, index) in slides">
                    <button x-on:click="currentSlideIndex = index + 1" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-neutral-600 dark:bg-neutral-300' : 'bg-neutral-600/50 dark:bg-neutral-300/50']"
                            x-bind:aria-label="'slide ' + (index + 1)" class="size-2 rounded-full transition bg-neutral-600 dark:bg-neutral-300"></button>
                </template>
            </div>
        </div>
    </section>

    <div class="w-full h-16"></div>

    <section class="max-w-3xl mx-auto px-8 py-6">
        <h1 class="font-pacifico text-5xl md:text-6xl text-zinc-800 text-center mb-8">Location</h1>
        <p class="font-pacifico text-zinc-700 text-lg text-center mb-16">Near us? Drop by our restaurant!</p>

        <div class="rounded-2xl p-6 bg-white shadow flex justify-center">
            <iframe class="w-full h-[45vh] rounded-xl"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.37882307004!2d106.90484158715823!3d-6.218193199999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698ca6299b71cb%3A0xf96065f7f34143d3!2sPizza%20Hut%20Restoran%20-%20Buaran%20Plaza!5e0!3m2!1sid!2sid!4v1757072454409!5m2!1sid!2sid"
                    style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <div class="w-full h-16"></div>

    <section class="mx-auto px-8 md:px-24 py-6">
        <div class="flex flex-wrap justify-center gap-6">
            @foreach (range(1, mt_rand(1, 6)) as $i)
                <article class="group rounded-2xl flex max-w-sm flex-col border border-neutral-300 bg-neutral-50 p-6 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                    <svg class="size-12 text-neutral-900 dark:text-white group-hover:scale-105 transition duration-500 ease-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path
                              d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388q0-.527.062-1.054.093-.558.31-.992t.559-.683q.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 9 7.558V11a1 1 0 0 0 1 1zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612q0-.527.062-1.054.094-.558.31-.992.217-.434.559-.683.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 3 7.558V11a1 1 0 0 0 1 1z" />
                    </svg>
                    <p class="mt-2 text-pretty text-sm">
                        Simply put, this software transformed my workflow! Its intuitive
                        interface and powerful features make tasks a breeze. A game-changer
                        for productivity!
                    </p>
                    <!-- avatar & rating -->
                    <div class="flex flex-col-reverse md:flex-row md:items-center mt-8 justify-between gap-6">
                        <!-- avatar & title -->
                        <div class="flex items-center gap-2">
                            <img class="size-10 rounded-full object-cover" src="https://penguinui.s3.amazonaws.com/component-assets/avatar-1.webp" alt="avatar" />
                            <div class="flex flex-col gap-1">
                                <h3 class="font-bold leading-4 text-neutral-900 dark:text-white">Bob Johnson</h3>
                                <span class="text-xs">CEO - TechNova</span>
                            </div>
                        </div>
                        <!-- Rating -->
                        <div class="flex items-center gap-1">
                            <span class="sr-only">Rated 4 stars</span>
                            <svg class="size-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                      clip-rule="evenodd" />
                            </svg>
                            <svg class="size-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                      clip-rule="evenodd" />
                            </svg>
                            <svg class="size-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                      clip-rule="evenodd" />
                            </svg>
                            <svg class="size-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                      clip-rule="evenodd" />
                            </svg>
                            <svg class="size-4 text-neutral-600/50 dark:text-neutral-300/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <div class="w-full h-16"></div>

    <footer class="relative">
        <img class="w-full h-full absolute inset-0 object-cover z-[1]" src="{{ asset('assets/images/sssquiggly.svg') }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E53935" fill-opacity="1"
                  d="M0,128L48,160C96,192,192,256,288,250.7C384,245,480,171,576,117.3C672,64,768,32,864,37.3C960,43,1056,85,1152,101.3C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
        <div class="bg-[#E53935]">
            <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
                <div class="md:flex md:justify-between">
                    <div class="mb-6 md:mb-0">
                        <a class="flex items-center relative z-10" href="/">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M8.18092 2.56556C7.90392 3.05195 7.65396 3.65447 7.416 4.36507C5.57795 9.34447 2.73476 16.6246 1.36225 20.12C0.73894 21.7073 2.25721 23.2963 3.87117 22.7465C7.38796 21.5484 14.6626 19.0869 19.6353 17.5194L19.6504 17.5145C20.3639 17.277 20.9659 17.0333 21.4491 16.7641C21.9273 16.4977 22.3551 16.1704 22.6426 15.7347C23.2987 14.7406 22.9351 13.6998 22.5012 12.8954C19.7712 7.83439 16.3585 4.2775 12.0968 1.5703C11.6898 1.31179 11.2341 1.09226 10.7418 1.02286C10.2141 0.948472 9.69595 1.05467 9.22968 1.36307C8.79315 1.65181 8.45686 2.08103 8.18092 2.56556ZM15.0912 9.09151C13.5105 7.4048 11.7893 5.97947 9.55526 4.3325C9.6817 4.01505 9.80284 3.75901 9.91885 3.55532C10.1115 3.21703 10.2575 3.08115 10.333 3.03119C10.3788 3.0009 10.4025 2.99481 10.4626 3.00327C10.5579 3.01672 10.7358 3.07517 11.0244 3.25848C14.994 5.78016 18.1714 9.08132 20.741 13.8449C21.0989 14.5085 20.9833 14.6233 20.9739 14.6325L20.9734 14.6331C20.9318 14.696 20.8089 14.8313 20.4757 15.017C20.2861 15.1226 20.0491 15.2333 19.7558 15.3501C18.0975 12.7134 16.6772 10.7839 15.0912 9.09151ZM13.6318 10.4591C15.0211 11.9415 16.2981 13.6452 17.8022 16.0033C12.9009 17.5716 6.46194 19.751 3.22621 20.8533L3.22459 20.8538L3.22391 20.8531L3.22329 20.8525L3.22387 20.851C4.48689 17.6345 7.00299 11.1934 8.83498 6.28876C10.7878 7.75003 12.2738 9.00998 13.6318 10.4591ZM10 13C11.1046 13 12 12.1046 12 11C12 9.89545 11.1046 9.00002 10 9.00002C8.89543 9.00002 8 9.89545 8 11C8 12.1046 8.89543 13 10 13ZM10 16C10 17.1046 9.10457 18 8 18C6.89543 18 6 17.1046 6 16C6 14.8954 6.89543 14 8 14C9.10457 14 10 14.8954 10 16ZM13 17C14.1046 17 15 16.1046 15 15C15 13.8954 14.1046 13 13 13C11.8954 13 11 13.8954 11 15C11 16.1046 11.8954 17 13 17Z"
                                          fill="#ffffff"></path>
                                </g>
                            </svg>
                            <p class="text-xl text-white font-extrabold leading-tight">{{ config('app.name') }}</p>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3 relative z-10">
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white">Resources</h2>
                            <ul class="text-zinc-100 font-medium">
                                <li class="mb-4">
                                    <a class="hover:underline" href="https://flowbite.com/">Flowbite</a>
                                </li>
                                <li>
                                    <a class="hover:underline" href="https://tailwindcss.com/">Tailwind CSS</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white">Follow us</h2>
                            <ul class="text-zinc-100 font-medium">
                                <li class="mb-4">
                                    <a class="hover:underline " href="https://github.com/themesberg/flowbite">Github</a>
                                </li>
                                <li>
                                    <a class="hover:underline" href="https://discord.gg/4eeurUVvTy">Discord</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white">Legal</h2>
                            <ul class="text-zinc-100 font-medium">
                                <li class="mb-4">
                                    <a class="hover:underline" href="#">Privacy Policy</a>
                                </li>
                                <li>
                                    <a class="hover:underline" href="#">Terms &amp; Conditions</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-zinc-200 sm:mx-auto lg:my-8" />
                <div class="sm:flex sm:items-center sm:justify-between relative z-10">
                    <span class="text-sm text-zinc-100 sm:text-center">© 2023 <a class="hover:underline" href="https://flowbite.com/">Flowbite™</a>. All Rights Reserved.
                    </span>
                    <div class="flex mt-4 sm:justify-center sm:mt-0">
                        <a class="text-zinc-100 hover:text-zinc-300" href="#">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                                <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Facebook page</span>
                        </a>
                        <a class="text-zinc-100 hover:text-zinc-300 ms-5" href="#">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 21 16">
                                <path
                                      d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z" />
                            </svg>
                            <span class="sr-only">Discord community</span>
                        </a>
                        <a class="text-zinc-100 hover:text-zinc-300 ms-5" href="#">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 17">
                                <path fill-rule="evenodd"
                                      d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Twitter page</span>
                        </a>
                        <a class="text-zinc-100 hover:text-zinc-300 ms-5" href="#">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">GitHub account</span>
                        </a>
                        <a class="text-zinc-100 hover:text-zinc-300 ms-5" href="#">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.094 20.094 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM8 1.707a8.821 8.821 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.758 45.758 0 0 0 8 1.707ZM1.642 8.262a8.57 8.57 0 0 1 4.73-5.981A53.998 53.998 0 0 1 9.54 7.222a32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.64 31.64 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM10 18.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 13.113 11a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Dribbble account</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</x-app>
