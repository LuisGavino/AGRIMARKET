@extends('layouts.admin')

@section('title', 'Settings · Security')
@section('heading', 'Account Settings')

@section('content')
<nav class="seg-tabs">
    <a href="{{ route('admin.settings.account') }}">Account</a>
    <a href="{{ route('admin.settings.security') }}" class="on">Security</a>
</nav>

<section class="panel pw">
    <h2>Password</h2>

    <form action="#" onsubmit="return false">
        <div class="grid-3">
            @foreach (['Old Password', 'New Password', 'Confirm Password'] as $i => $label)
                <div class="fld">
                    <label for="pw{{ $i }}">{{ $label }}</label>
                    <div class="in" style="padding-right:12px">
                        <input id="pw{{ $i }}" type="password" style="border:0;outline:0;width:100%;font:inherit;background:none" autocomplete="off">
                        <button type="button" aria-label="Show / hide password" onclick="const f=this.previousElementSibling;f.type=f.type==='password'?'text':'password'">
                            <x-admin.icon name="eye-off" :size="24" :stroke="1.5" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <ul class="rules" style="margin-top:20px">
            <li><x-admin.icon name="check-circle" :size="26" :stroke="1.5" /> Minimum 8 characters.</li>
            <li><x-admin.icon name="check-circle" :size="26" :stroke="1.5" /> Use combination of uppercase and lowercase letters.</li>
            <li><x-admin.icon name="check-circle" :size="26" :stroke="1.5" /> Use of special characters (e.g., !, @, #, $, %)</li>
        </ul>

        <div class="actions">
            <button class="btn-blue" type="submit">Update Password</button>
            <a class="cancel" href="#">Cancel</a>
        </div>
    </form>
</section>
@endsection
