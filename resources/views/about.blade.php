<x-app title="Abous Us">

    <x-slot name="head">
        <style>
            .font-pacifico {
                font-family: 'Pacifico', cursive;
            }

            .text-stroke {
                -webkit-text-stroke: 2px #fff;
                color: transparent;
            }
        </style>
    </x-slot>

    <x-navbar />

    <main x-init="$store.prefs.setTheme('light')" x-init="$watch('$store.prefs.state.theme', () => $store.prefs.setTheme('light'))" id="main">

        <div class="flex flex-col min-h-[25vh] relative overflow-hidden">

            <img class="w-full h-[50vh] absolute inset-0 object-cover" src="{{ asset('assets/images/sssquiggly.svg') }}">

            <div class="bg-[#E53935] py-6 px-8 md:px-24 ">

                <img class="absolute top-0 right-0 translate-x-1/3 md:translate-x-1/2 md:-translate-y-1/2 w-1/2 aspect-square object-contain" src="{{ asset('assets/images/pizza.jpg') }}" alt="Crazy Pizza" draggable="false">

                <div class="max-w-xl md:ml-24 mt-24 mx-auto relative z-10">
                    <h1 class="text-2xl text-white md:text-3xl font-extrabold leading-tight ">
                        About Us
                    </h1>

                    <div class="w-full h-8"></div>

                    <p class="italic font-medium text-white">Get to know why we're here!</p>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#E53935" fill-opacity="1"
                      d="M0,64L40,64C80,64,160,64,240,69.3C320,75,400,85,480,80C560,75,640,53,720,80C800,107,880,181,960,197.3C1040,213,1120,171,1200,160C1280,149,1360,171,1400,181.3L1440,192L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
                </path>
            </svg>

            <div class="max-w-prose mx-auto my-16">
                <article class="prose">
                    @php
                        $content = "
# About Crazy Pizza

Welcome to **Crazy Pizza**, a small, family-owned pizzeria with a big passion for unforgettable flavors! We started Crazy Pizza with a simple dream: to bring fresh, creative, and downright crazy-delicious pizzas to our local community.

## Our Story
Crazy Pizza began as a weekend project in our tiny kitchen, where friends and family gathered to test out bold toppings and playful recipes. What started as a hobby quickly grew into a neighborhood favorite, thanks to the support of our amazing customers.

## What Makes Us Different
- **Handmade Dough** – Prepared fresh daily for that perfect, chewy crust.
- **Locally Sourced Ingredients** – We partner with nearby farms and suppliers to keep our pizzas fresh and support local businesses.
- **Crazy Good Combinations** – From classic Margheritas to adventurous topping combos you won’t find anywhere else.

## Our Mission
We’re more than just pizza—we’re about community. Every slice we serve represents our commitment to quality, creativity, and the people who inspire us. Crazy Pizza exists to make your day a little brighter, one bite at a time.

## Location
📍 **123 Main Street, Springfield**
Come visit us in the heart of town! Our cozy shop is the perfect spot to grab a fresh slice or enjoy a meal with friends and family.

## Visit Us
Whether you’re grabbing a quick slice or hosting a pizza party, Crazy Pizza is here to bring the fun and flavor. Stop by, say hi, and taste the crazy difference!


Disclaimer: This content is provided solely for demonstration purposes within our CMS app. Any names, locations, or details mentioned are entirely fictional or coincidental and should not be interpreted as real or representative of any actual business or person. If you're interested for a custom one, feel free to contact us [here](mailto:kevindarmawan2outlook.com).


                        ";
                    @endphp
                    {!! \Illuminate\Support\Str::markdown($content) !!}
                </article>
            </div>

        </div>

    </main>

    <x-footer />

</x-app>
