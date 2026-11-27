@extends('layouts.seller')

@section('title', 'Active Listings – AgriMarket')

@php
    // Static sample data (front-end only).
    $listings = [
        ['title' => 'Corn Seeds', 'price' => 'PHP 300', 'listed' => '1/1/2026', 'category' => 'Seeds', 'location' => 'Surigao City, Surigao del Norte'],
        ['title' => 'Corn Seeds', 'price' => 'PHP 300', 'listed' => '1/1/2026', 'category' => 'Seeds', 'location' => 'Surigao City, Surigao del Norte'],
    ];
@endphp

@section('seller_content')
    <h1>Active Listings</h1>

    @foreach ($listings as $listing)
        <article class="listing">
            <img src="{{ asset('images/listing-corn-seeds.jpg') }}" alt="{{ $listing['title'] }}" class="listing__img">

            <div class="listing__body">
                <h2 class="listing__title">{{ $listing['title'] }}</h2>
                <div class="listing__price">{{ $listing['price'] }}</div>
                <div class="listing__meta">
                    Active Listing - Listed on {{ $listing['listed'] }}<br>
                    Listed on {{ $listing['category'] }} Category<br>
                    {{ $listing['location'] }}
                </div>

                <div class="listing__actions">
                    <button type="button" class="btn btn--gray"><x-icon name="circle-check" :size="18" /> Mark as sold</button>
                    <button type="button" class="btn btn--gray"><x-icon name="trending-up" :size="18" /> Advertise</button>
                    <button type="button" class="btn btn--gray"><x-icon name="share" :size="18" /> Share</button>
                </div>
            </div>
        </article>
    @endforeach
@endsection
