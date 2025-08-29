<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    {{-- STYLES --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (isset($head))
        {{ $head }}
    @endif

</head>

<body class="text-[#1b1b18] dark:text-[#fdfdfc] antialiased">

    {{ $slot }}

</body>

</html>
