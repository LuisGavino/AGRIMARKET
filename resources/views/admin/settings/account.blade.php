@extends('layouts.admin')

@section('title', 'Settings')
@section('heading', 'Account Settings')

@section('content')
<nav class="seg-tabs">
    <a href="{{ route('admin.settings.account') }}" class="on">Account</a>
    <a href="{{ route('admin.settings.security') }}">Security</a>
</nav>

<section class="panel">
    <h2>Profile Information</h2>

    <div class="profile-pic">
        <img src="{{ asset('images/avatar.jpg') }}" alt="">
        <a class="btn-line" href="#">Change Pictures <x-admin.icon name="edit" :size="22" :stroke="1.5" /></a>
    </div>

    <form action="#" onsubmit="return false">
        <div class="grid-3">
            <div class="fld"><label for="first">First Name</label><input class="in" id="first" value="Antonio Luis"></div>
            <div class="fld"><label for="last">Last Name</label><input class="in" id="last" value="Gavino"></div>
            <div class="fld"><label for="email">Email</label><input class="in" id="email" type="email" value="agavino@ssct.edu.ph"></div>
        </div>
        <div class="grid-3 two">
            <div class="fld"><label for="gender">Gender</label><input class="in" id="gender" value="Male"></div>
            <div class="fld"><label for="dob">Date Birthday</label><input class="in" id="dob" value="30/12/2005"></div>
        </div>
        <div class="actions">
            <button class="btn-blue" type="submit">Update</button>
            <a class="cancel" href="#">Cancel</a>
        </div>
    </form>
</section>

<section class="panel">
    <div class="row-h">
        <h2>Contact Detail</h2>
        <a class="btn-line" href="#">Edit <x-admin.icon name="edit" :size="22" :stroke="1.5" /></a>
    </div>
    <div class="grid-3">
        <div class="fld"><label>Phone Number</label><div class="in ro">+639763148428</div></div>
        <div class="fld"><label>Country</label><div class="in ro">Philippines</div></div>
        <div class="fld"><label>Address</label><div class="in ro" style="height:auto;min-height:51px;padding-block:8px;line-height:1.3">Brgy. San Juan, Surigao City, Surigao del Norte</div></div>
    </div>
</section>
@endsection
