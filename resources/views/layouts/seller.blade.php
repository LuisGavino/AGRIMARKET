{{-- Seller pages: app sidebar + "Seller Dashboard" sub-navigation --}}
@extends('layouts.app')

@section('content')
    <div class="seller">
        <nav class="seller__nav" aria-label="Seller dashboard">
            <h2>Seller Dashboard</h2>
            <a href="#"><x-icon name="clipboard" :size="14" /> All reports</a>
            <a href="{{ route('seller.choose-type') }}" @class(['is-active' => request()->routeIs('seller.choose-type', 'seller.create')])>
                <x-icon name="globe" :size="14" /> Create new listing
            </a>
            <a href="{{ route('seller.listings') }}" @class(['is-active' => request()->routeIs('seller.listings')])>
                <x-icon name="check-square" :size="14" /> Active Listings
            </a>
        </nav>

        <div class="seller__body">
            @yield('seller_content')
        </div>
    </div>
@endsection
