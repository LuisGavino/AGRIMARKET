@extends('layouts.public')

@section('title', 'Sign Up – AgriMarket')
@section('body_class', 'public--gradient')

@section('content')
    <section class="auth">
        <form class="auth__form" action="#" method="post" onsubmit="return false;">
            <h1>Get Started Now</h1>

            <div class="auth__fields">
                <div>
                    <label class="auth__label" for="name">Name</label>
                    <input class="auth__input" id="name" name="name" type="text" placeholder="Enter your name">
                </div>
                <div>
                    <label class="auth__label" for="email">Email address</label>
                    <input class="auth__input" id="email" name="email" type="email" placeholder="Enter your email">
                </div>
                <div>
                    <label class="auth__label" for="password">Password</label>
                    <input class="auth__input" id="password" name="password" type="password" placeholder="Enter your password">
                </div>

                <label class="auth__check">
                    <input type="checkbox" name="terms"> I agree to the <a href="#">terms &amp; policy</a>
                </label>

                <button type="submit" class="auth__submit">Signup</button>
            </div>

            <div class="auth__divider"><span>Or</span></div>

            @include('auth._social')

            <p class="auth__switch">Have an account? <a href="{{ route('login') }}">Sign In</a></p>
        </form>

        @include('auth._aside')
    </section>
@endsection
