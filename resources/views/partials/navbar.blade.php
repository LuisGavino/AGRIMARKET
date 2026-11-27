<header class="navbar">
    <div class="navbar__inner">
        @include('partials.brand')

        <nav class="navbar__menu" aria-label="Primary">
            <a href="{{ route('landing') }}" @class(['is-active' => request()->routeIs('landing')])>Home</a>
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>
            <a href="{{ route('products') }}" @class(['is-active' => request()->routeIs('products')])>Products</a>
        </nav>

        <div class="navbar__actions">
            <a href="{{ route('login') }}" class="btn">Login</a>
            <a href="{{ route('signup') }}" class="btn">Sign Up</a>
        </div>
    </div>
</header>
