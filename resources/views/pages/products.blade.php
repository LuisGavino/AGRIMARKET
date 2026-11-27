@extends('layouts.public')

@section('title', 'Products – AgriMarket')
@section('body_class', 'public--gradient')

@php
    // Static sample data so the page renders without a database.
    $featured = [
        ['name' => 'Green Apple',     'price' => '199'],
        ['name' => 'Chanise Cabbage', 'price' => '149'],
        ['name' => 'Green Capsicum',  'price' => '14.99'],
        ['name' => 'Ladies Finger',   'price' => '14.99'],
    ];

    $categories = [
        ['label' => 'Fresh Fruit',      'image' => 'fresh-fruit.png'],
        ['label' => 'Fresh Vegetables', 'image' => 'fresh-vegetables.png'],
        ['label' => 'Meat & Fish',      'image' => 'meat-fish.png'],
        ['label' => 'Livestock',        'image' => 'livestock.png'],
        ['label' => 'Seeds',            'image' => 'seeds.png'],
        ['label' => 'Poultry',          'image' => 'poultry.png'],
    ];
@endphp

@section('content')
    <div class="products-wrap">
        <x-section-heading title="Featured Products" />

        <div class="product-grid">
            @foreach ($featured as $product)
                <x-product-card :name="$product['name']" :price="$product['price']" />
            @endforeach
        </div>

        <x-section-heading title="Popular Categories" />

        <div class="category-grid">
            @foreach ($categories as $category)
                <x-category-card :label="$category['label']" :image="$category['image']" />
            @endforeach
        </div>
    </div>
@endsection
