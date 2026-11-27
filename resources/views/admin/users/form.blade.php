@extends('layouts.admin')

@php $editing = $user->exists; @endphp

@section('title', 'Users')
@section('heading', $editing ? 'Edit User' : 'Add User')

@section('content')
<section class="panel">
    <h2>Account Information</h2>

    <form method="post" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" autocomplete="off">
        @csrf
        @if ($editing) @method('PUT') @endif

        @include('partials.errors')

        <div class="grid-3">
            <div class="fld">
                <label for="name">Name</label>
                <input class="in" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                @error('name')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="email">Email</label>
                <input class="in" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                @error('email')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="role">Role</label>
                <select class="in" id="role" name="role">
                    @foreach ([\App\Models\User::ROLE_CUSTOMER, \App\Models\User::ROLE_ADMIN] as $role)
                        <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('role')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid-3 two" style="margin-top:16px">
            <div class="fld">
                <label for="password">{{ $editing ? 'New Password (leave blank to keep)' : 'Password' }}</label>
                <input class="in" id="password" name="password" type="password" autocomplete="new-password" @required(! $editing)>
                @error('password')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="password_confirmation">Confirm Password</label>
                <input class="in" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" @required(! $editing)>
            </div>
        </div>

        <div class="actions">
            <button class="btn-blue" type="submit">{{ $editing ? 'Update' : 'Create' }}</button>
            <a class="cancel" href="{{ route('admin.users') }}">Cancel</a>
        </div>
    </form>
</section>
@endsection
