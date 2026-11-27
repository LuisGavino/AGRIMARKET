@php
    $links = [
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
        ['route' => 'admin.users', 'match' => 'admin.users', 'label' => 'Users', 'icon' => 'users'],
        ['route' => 'admin.produce', 'match' => 'admin.produce', 'label' => 'Produce', 'icon' => 'leaf'],
        ['route' => 'admin.livestock', 'match' => 'admin.livestock', 'label' => 'Livestock', 'icon' => 'cow'],
        ['route' => 'admin.service-providers', 'match' => 'admin.service-providers', 'label' => 'Service Providers', 'icon' => 'bolt'],
        ['route' => 'admin.settings.account', 'match' => 'admin.settings.*', 'label' => 'Settings', 'icon' => 'settings'],
    ];
@endphp

<aside class="sidebar">
    <a class="logo" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/logo.png') }}" alt="AgriMarket">
    </a>

    <label class="side-search">
        <x-admin.icon name="search" :size="28" />
        <input type="search" placeholder="Search AgriMarket">
    </label>

    <nav class="side-nav">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" @class(['is-active' => request()->routeIs($link['match'])])>
                <span class="ico">
                    @if ($link['icon'] === 'cow')
                        <img class="cow" src="{{ asset('images/icons/cow.png') }}" alt="">
                    @else
                        <x-admin.icon :name="$link['icon']" :size="30" />
                    @endif
                </span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="side-foot">
        <div class="me">
            <div class="av"><img src="{{ asset('images/avatar.jpg') }}" alt=""></div>
            <div>
                <b>{{ auth()->user()?->name }}</b>
                <small>Admin</small>
            </div>
        </div>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout">
                <x-admin.icon name="logout" :size="34" :stroke="1.5" />
                Log Out
            </button>
        </form>
    </div>
</aside>
