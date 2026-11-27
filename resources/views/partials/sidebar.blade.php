@php
    $links = [
        ['route' => 'home',          'match' => ['home'],                         'icon' => 'home',     'label' => 'Home'],
        ['route' => 'notifications', 'match' => ['notifications'],                'icon' => 'bell',     'label' => 'Notifications'],
        ['route' => 'messages',      'match' => ['messages'],                     'icon' => 'chat',     'label' => 'Messages'],
        ['route' => 'wishlist',      'match' => ['wishlist', 'checkout'],         'icon' => 'cart',     'label' => 'Wishlist'],
        ['route' => 'seller.listings','match' => ['seller.*'],                    'icon' => 'tag',      'label' => 'Selling'],
        ['route' => 'settings.account','match' => ['settings.*'],                 'icon' => 'settings', 'label' => 'Settings'],
    ];
@endphp

<aside class="sidebar">
    @include('partials.brand')

    <button type="button" class="sidebar__search" data-search-open>
        <x-icon name="search" :size="28" />
        <span>Search AgriMarket</span>
    </button>

    <nav class="sidebar__nav" aria-label="Main">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" @class(['is-active' => request()->routeIs(...$link['match'])])>
                <x-icon :name="$link['icon']" :size="30" />
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="sidebar__footer">
        <div class="sidebar__user">
            <div class="sidebar__avatar">
                <img src="{{ asset('images/avatar-user.jpg') }}" alt="">
            </div>
            <div>
                <div class="sidebar__name">{{ auth()->user()?->name }}</div>
                <div class="sidebar__role">{{ auth()->user()?->isAdmin() ? 'Admin' : 'User' }}</div>
            </div>
        </div>

        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar__logout">
                <x-icon name="logout" :size="30" /> Log Out
            </button>
        </form>
    </div>
</aside>
