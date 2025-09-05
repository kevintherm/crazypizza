<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Pacifico&display=swap" rel="stylesheet">

    {{-- STYLES --}}
    @vite('resources/css/app.css')

    @auth
        @vite('resources/js/app.js')
    @else
        @vite('resources/js/guest.js')
    @endauth

    @if (isset($head))
        {{ $head }}
    @endif

    <script>
        (function() {
            const key = 'user:prefs:v1';
            let state = {
                theme: 'system'
            };

            try {
                const raw = localStorage.getItem(key);
                if (raw) {
                    const stored = JSON.parse(raw);
                    Object.assign(state, stored);
                }
            } catch (e) {
                console.warn('prefs: failed to load theme from localStorage', e);
            }

            function effectiveTheme() {
                if (state.theme === 'dark') return 'dark';
                if (state.theme === 'light') return 'light';
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'dark' :
                    'light';
            }

            if (effectiveTheme() === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

</head>

<body x-data class="bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark antialiased overflow-x-hidden">

    {{ $slot }}

    @if (isset($foot))
        {{ $foot }}
    @endif
</body>

</html>
