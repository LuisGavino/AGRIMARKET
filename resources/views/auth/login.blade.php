@extends('layouts.public')

@section('title', 'Login – AgriMarket')
@section('body_class', 'public--gradient')

@section('content')
    <section class="auth">
        <form class="auth__form" action="{{ route($redirectRoute ?? 'home') }}" method="get">
            <h1>Welcome back!</h1>
            <p class="auth__sub">Enter your Credentials to access your account</p>

            <div class="auth__fields">
                <div>
                    <label class="auth__label" for="email">Email address</label>
                    <input class="auth__input" id="email" name="email" type="email">
                </div>
                <div>
                    <label class="auth__label" for="password">
                        Password <a href="#">forgot password</a>
                    </label>
                    <input class="auth__input" id="password" name="password" type="password" placeholder="Enter your password">
                </div>

                <label class="auth__check">
                    <input type="checkbox" name="remember"> Save Login
                </label>

                <button type="submit" class="auth__submit">Login</button>
            </div>

            <div class="auth__spacer"></div>

            @include('auth._social')

            <p class="auth__switch">Don’t have an account? <a href="{{ route('signup') }}">Sign Up</a></p>
        </form>

        @include('auth._aside')
    </section>
@endsection
