@props([
    'id' => 'modal',
    'containerClass' => '',
    'backdropClass' => '',
    'static' => false,
    'attributes' => [],
])

<div x-data="{ modalOpen: false }">
    <div x-ref="{{ $id }}" id="{{ $id }}" x-cloak x-show="modalOpen"
        x-on:modal="
                if ($event.detail.action === 'toggle') modalOpen = !modalOpen;
                else if ($event.detail.action === 'show') modalOpen = true;
                else if ($event.detail.action === 'hide') modalOpen = false;
            "
        x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalOpen" x-on:keydown.esc.window="modalOpen = false"
        @if (!$static) @click.self="modalOpen = false" @endif
        class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 backdrop-blur-md sm:items-baseline {{ $containerClass }}"
        role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-50"
            @if ($static) x-on:click.outside="$el.classList.add('shake');setTimeout(() => ($el.classList.remove('shake')), 300)" @endif
            {{ $attributes->merge(['class' => 'flex w-full max-w-xl flex-col gap-4 overflow-hidden rounded-t-lg sm:rounded-radius bg-surface text-on-surface dark:bg-surface-dark-alt dark:text-on-surface-dark sm:mt-32']) }}>
            {{ $slot }}
        </div>
    </div>
</div>
