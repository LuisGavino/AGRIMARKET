@extends('layouts.admin')

@section('title', 'Produce')
@section('heading', 'Manage Produce Listings')

@php
    $items = [
        ['name' => 'Watermelon Seeds', 'image' => 'products/watermelon-seeds.jpg', 'listings' => 25],
        ['name' => 'Sunflower', 'image' => 'products/sunflower.jpg', 'listings' => 20],
        ['name' => 'Banana', 'image' => 'products/banana.jpg', 'listings' => 35],
        ['name' => 'Apple', 'image' => 'products/apple.jpg', 'listings' => 40],
        ['name' => 'Grapes', 'image' => 'products/grapes.jpg', 'listings' => 45],
        ['name' => 'Hemp Seeds', 'image' => 'products/hemp-seeds.jpg', 'listings' => 45],
    ];
@endphp

@section('content')
    <x-admin.listing-page :items="$items" />
@endsection
