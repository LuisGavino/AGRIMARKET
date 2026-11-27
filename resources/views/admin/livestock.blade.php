@extends('layouts.admin')

@section('title', 'Livestock')
@section('heading', 'Manage Livestock Listings')

@php
    $items = [
        ['name' => 'Chicken', 'image' => 'products/chicken.jpg', 'listings' => 25],
        ['name' => 'Ducks', 'image' => 'products/ducks.jpg', 'listings' => 20],
        ['name' => 'Cows', 'image' => 'products/cows.jpg', 'listings' => 35],
        ['name' => 'Pigs', 'image' => 'products/pigs.jpg', 'listings' => 40],
        ['name' => 'Goat', 'image' => 'products/goat.jpg', 'listings' => 45],
        ['name' => 'Rabbit', 'image' => 'products/rabbit.jpg', 'listings' => 45],
    ];
@endphp

@section('content')
    <x-admin.listing-page :items="$items" />
@endsection
