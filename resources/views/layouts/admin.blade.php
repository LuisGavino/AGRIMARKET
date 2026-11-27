<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · AgriMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Nunito+Sans:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin">
    <div class="admin-shell">
        <x-admin.sidebar />

        <main class="main">
            @include('partials.flash')
            @foreach (['delete', 'status'] as $key)
                @error($key)<div class="errors" role="alert">{{ $message }}</div>@enderror
            @endforeach
            <h1 class="page-title">@yield('heading')</h1>
            @yield('content')
        </main>
    </div>
</body>
</html>
