<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgriMarket')</title>
    @include('partials.fonts')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app">
        @include('partials.sidebar')

        <div class="app-main">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>

    @include('partials.search-overlay', ['open' => trim($__env->yieldContent('search_open')) === '1'])

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
