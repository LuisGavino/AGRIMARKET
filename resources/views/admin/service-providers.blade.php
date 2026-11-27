@extends('layouts.admin')

@section('title', 'Service Providers')
@section('heading', 'Manage Service Provider Listings')

@php
    $items = [
        ['name' => 'Tractor & Farm Equipment Rental', 'image' => 'products/tractor-rental.jpg', 'listings' => 25],
        ['name' => 'Crop Planting Services', 'image' => 'products/crop-planting.jpg', 'listings' => 20],
        ['name' => 'Harvesting Services', 'image' => 'products/harvesting.jpg', 'listings' => 35],
        ['name' => 'Veterinary Services', 'image' => 'products/veterinary.jpg', 'listings' => 40],
        ['name' => 'Agricultural Transport', 'image' => 'products/transport.jpg', 'listings' => 45],
        ['name' => 'Pest Control', 'image' => 'products/pest-control.jpg', 'listings' => 45],
    ];
@endphp

@section('content')
    <x-admin.listing-page :items="$items" />
@endsection
