<header class="site-header">
    <a class="logo" href="{{ route('home') }}">
        <img src="{{ asset('images/logo.png') }}" alt="AgriMarket">
    </a>

    <nav class="site-nav">
        <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a>
        <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>
        <a href="{{ route('products') }}" @class(['is-active' => request()->routeIs('products')])>Products</a>
    </nav>

    <div class="site-actions">
        <a class="btn-yellow" href="{{ route('login') }}">Login</a>
        <a class="btn-yellow" href="{{ route('signup') }}">Sign Up</a>
    </div>

    <div class="torn"></div>
</header>
