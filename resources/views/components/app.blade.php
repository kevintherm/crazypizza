<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    {{-- STYLES --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (isset($head))
        {{ $head }}
    @endif

</head>

<body class="bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark antialiased">

    {{ $slot }}

    @if (isset($foot))
        {{ $foot }}
    @endif
</body>

</html>
