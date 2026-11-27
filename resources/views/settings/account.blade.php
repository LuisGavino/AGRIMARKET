@extends('layouts.app')

@section('title', 'Account Settings – AgriMarket')

@section('content')
    <div class="app-content settings">
        <h1>Account Settings</h1>

        @include('settings._tabs')

        <section class="card">
            <h2>Profile Information</h2>

            <div class="profile-pic">
                <img src="{{ asset('images/avatar-user.jpg') }}" alt="Profile picture">
                <button type="button" class="btn btn--outline">Change Pictures <x-icon name="edit" :size="18" /></button>
            </div>

            <form action="#" method="post" onsubmit="return false;" class="form-stack">
                <div class="form-row form-row--3">
                    <x-field label="First Name" name="first_name" value="Antonio Luis" />
                    <x-field label="Last Name" name="last_name" value="Gavino" />
                    <x-field label="Email" name="email" type="email" value="agavino@ssct.edu.ph" />
                </div>
                <div class="form-row form-row--3">
                    <x-field label="Gender" name="gender" value="Male" />
                    <x-field label="Date Birthday" name="birthday" value="30/12/2005" />
                </div>

                <div class="settings__cta">
                    <button type="submit" class="btn btn--blue">Update</button>
                    <a href="#" class="link">Cancel</a>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="card__header">
                <h2>Contact Detail</h2>
                <button type="button" class="btn btn--outline">Edit <x-icon name="edit" :size="18" /></button>
            </div>

            <div class="form-row form-row--3">
                <x-field label="Phone Number" name="phone" value="+639763148428" :disabled="true" />
                <x-field label="Country" name="country" value="Philippines" :disabled="true" />
                <x-field label="Address" name="address" value="Brgy. San Juan, Surigao City, Surigao del Norte" :disabled="true" />
            </div>
        </section>
    </div>
@endsection
