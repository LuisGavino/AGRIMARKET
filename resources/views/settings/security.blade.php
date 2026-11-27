@extends('layouts.app')

@section('title', 'Security – AgriMarket')

@section('content')
    <div class="app-content settings">
        <h1>Account Settings</h1>

        @include('settings._tabs')

        <section class="card">
            <h2>Password</h2>

            <form action="#" method="post" onsubmit="return false;" style="margin-top:20px">
                <div class="form-row form-row--3">
                    <x-field label="Old Password" name="old_password" :password="true" />
                    <x-field label="New Password" name="new_password" :password="true" />
                    <x-field label="Confirm Password" name="confirm_password" :password="true" />
                </div>

                <ul class="rules">
                    <li><x-icon name="check-circle" :size="18" /> Minimum 8 characters.</li>
                    <li><x-icon name="check-circle" :size="18" /> Use combination of uppercase and lowercase letters.</li>
                    <li><x-icon name="check-circle" :size="18" /> Use of special characters (e.g., !, @, #, $, %)</li>
                </ul>

                <div class="settings__cta">
                    <button type="submit" class="btn btn--blue">Update Password</button>
                    <a href="#" class="link">Cancel</a>
                </div>
            </form>
        </section>
    </div>
@endsection
