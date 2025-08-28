<div x-data="{
    showBanner: false,
    bannerText: '',
    lifespan: 5000,
    targetHref: '',
    openNewWindow: false,

    triggerBanner({ message, target = '', openNewWindow = false, duration = 5000 }) {
        this.openNewWindow = openNewWindow;
        this.targetHref = target;
        this.bannerText = message;
        this.lifespan = duration;
        this.showBanner = true;
    },
    closeBanner(val) {
        if (val) {
            setTimeout(() => {
                this.showBanner = false;
            }, this.lifespan);
        }
    }
}" x-on:banner.window="triggerBanner($event.detail)" x-cloak x-show="showBanner"
    x-init="$watch('showBanner', (val) => closeBanner(val))" x-transition:enter="transition ease-out duration-350"
    x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-350" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-4"
    class="relative flex border-outline bg-surface-alt p-4 text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark border-b">
    <div class="mx-auto flex flex-wrap items-center gap-2 px-6">
        <p class="sm:text-sm text-pretty text-xs" x-text="bannerText"></p>
        <a x-show="targetHref.length" :href="targetHref" :target="openNewWindow ? '_blank' : '_self'"
            class="whitespace-nowrap bg-primary px-4 py-1 text-center text-xs font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark rounded-radius">
            View
        </a>
    </div>
    <button @click="showBanner = false" class="absolute top-1/2 -translate-y-1/2 right-4" aria-label="dismiss banner">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
            fill="none" stroke-width="2.5" class="size-4 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
