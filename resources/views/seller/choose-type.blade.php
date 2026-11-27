@extends('layouts.seller')

@section('title', 'Choose Listing Type – AgriMarket')

@php
    $types = [
        ['key' => 'produce',   'label' => 'Produce',   'image' => 'listing-type-produce.jpg',   'text' => 'List fruits, vegetables, crops, and other farm-grown products for sale.'],
        ['key' => 'livestock', 'label' => 'Livestock', 'image' => 'listing-type-livestock.jpg', 'text' => 'List animals such as cattle, pigs, chickens, goats, and other livestock for sale.'],
        // NOTE: the Figma "Service" card reuses the Produce description; swap in service-specific copy if you want.
        ['key' => 'service',   'label' => 'Service',   'image' => 'listing-type-service.jpg',   'text' => 'List fruits, vegetables, crops, and other farm-grown products for sale.'],
    ];
@endphp

@section('seller_content')
    <h1 style="margin-top:36px">Choose Listing Type</h1>

    <div class="type-grid">
        @foreach ($types as $type)
            <a href="{{ route('seller.create', $type['key']) }}" class="type-card">
                <img src="{{ asset('images/' . $type['image']) }}" alt="{{ $type['label'] }}">
                <p>{{ $type['text'] }}</p>
                <span class="btn btn--gray">{{ $type['label'] }}</span>
            </a>
        @endforeach
    </div>
@endsection
