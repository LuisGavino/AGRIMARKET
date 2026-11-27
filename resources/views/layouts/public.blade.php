<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgriMarket')</title>
    @include('partials.fonts')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="public @yield('body_class')">
    @include('partials.navbar')

    <main class="public-main">
        @include('partials.flash')
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
