<x-app title="Login">
    <div class="flex h-svh items-center justify-center">
        <form method="POST" action="{{ route('login') }}"
            class="md:rounded-radius border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark group flex w-full flex-col overflow-hidden md:max-w-sm md:border">
            @csrf
            <div class="flex flex-col gap-4 p-6">
                <a href="/">
                    <span class="text-sm font-medium">{{ config('app.name') }}</span>
                </a>
                <h3 class="text-on-surface-strong dark:text-on-surface-dark-strong text-balance text-xl font-bold lg:text-2xl"
                    aria-describedby="featureDescription">Login</h3>

                <div class="text-on-surface dark:text-on-surface-dark flex w-full flex-col gap-1">
                    <label for="email" class="w-fit pl-0.5 text-sm">Email</label>
                    <input x-data id="email" type="email"
                        class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                        :value="'{{ old('email') }}'" name="email" placeholder="Enter your email"
                        autocomplete="email" required />
                </div>

                <div class="text-on-surface dark:text-on-surface-dark flex w-full flex-col gap-1">
                    <label for="passwordInput" class="w-fit pl-0.5 text-sm">Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <input x-bind:type="showPassword ? 'text' : 'password'" id="passwordInput"
                            class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            name="password" autocomplete="current-password" placeholder="Enter your password"
                            required />

                        <button type="button" x-on:click="showPassword = !showPassword"
                            class="text-on-surface dark:text-on-surface-dark absolute right-2.5 top-1/2 -translate-y-1/2"
                            aria-label="Show password">
                            <svg x-cloak x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-cloak x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex w-full justify-between">
                    <label for="rememberMe"
                        class="text-on-surface dark:text-on-surface-dark has-checked:text-on-surface-strong dark:has-checked:text-on-surface-dark-strong has-disabled:cursor-not-allowed has-disabled:opacity-75 flex items-center gap-2 text-sm font-medium">
                        <span class="relative flex items-center">
                            <input id="rememberMe" type="checkbox"
                                class="before:content[''] border-outline bg-surface-alt checked:border-primary checked:before:bg-primary focus:outline-outline-strong checked:focus:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark peer relative size-4 appearance-none overflow-hidden rounded-sm border before:absolute before:inset-0 focus:outline-2 focus:outline-offset-2 active:outline-offset-0 disabled:cursor-not-allowed"
                                name="remember" />
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                                stroke="currentColor" fill="none" stroke-width="4"
                                class="text-on-primary dark:text-on-primary-dark pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span>Remember Me</span>
                    </label>

                    <a href="#"
                        class="text-primary focus:outline-hidden dark:text-primary-dark font-medium underline-offset-2 hover:underline focus:underline">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit"
                    class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75">Login</button>

            </div>
        </form>
    </div>

    <x-toast :duration="5000" />

    @error('message')
        <script>
            window.addEventListener('alpine:init', () => {
                document.addEventListener('DOMContentLoaded', () => {
                    $.store('notifiers').toast({
                        variant: 'danger',
                        title: 'Oops...',
                        message: @js($message)
                    });
                })
            });
        </script>
    @enderror

</x-app>
