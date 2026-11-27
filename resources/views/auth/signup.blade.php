@extends('layouts.public')

@section('title', 'Sign Up – AgriMarket')
@section('body_class', 'public--gradient')

@section('content')
    <section class="auth">
        <form class="auth__form" action="{{ route('signup.store') }}" method="post">
            @csrf
            <h1>Get Started Now</h1>

            <div class="auth__fields">
                @include('auth._errors')
                <div>
                    <label class="auth__label" for="name">Name</label>
                    <input class="auth__input" id="name" name="name" type="text" placeholder="Enter your name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                </div>
                <div>
                    <label class="auth__label" for="email">Email address</label>
                    <input class="auth__input" id="email" name="email" type="email" placeholder="Enter your email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                </div>
                <div>
                    <label class="auth__label" for="password">Password</label>
                    <input class="auth__input" id="password" name="password" type="password" placeholder="Enter your password" autocomplete="new-password" required>
                </div>
                <div>
                    <label class="auth__label" for="password_confirmation">Confirm Password</label>
                    <input class="auth__input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm your password" autocomplete="new-password" required>
                </div>

                <label class="auth__check">
                    <input type="checkbox" name="terms" value="1" @checked(old('terms')) required> I agree to the <a href="#">terms &amp; policy</a>
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
