<nav class="tabs" aria-label="Account settings">
    <a href="{{ route('settings.account') }}" @class(['is-active' => request()->routeIs('settings.account')])>Account</a>
    <a href="{{ route('settings.security') }}" @class(['is-active' => request()->routeIs('settings.security')])>Security</a>
</nav>
