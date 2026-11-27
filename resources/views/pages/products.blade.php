@extends('layouts.public')

@section('title', 'Products – AgriMarket')
@section('body_class', 'public--gradient')

@php
    // Popular categories keep the original design; the product grid below is database-backed.
    $categories = [
        ['label' => 'Fresh Fruit',      'image' => 'fresh-fruit.png'],
        ['label' => 'Fresh Vegetables', 'image' => 'fresh-vegetables.png'],
        ['label' => 'Meat & Fish',      'image' => 'meat-fish.png'],
        ['label' => 'Livestock',        'image' => 'livestock.png'],
        ['label' => 'Seeds',            'image' => 'seeds.png'],
        ['label' => 'Poultry',          'image' => 'poultry.png'],
    ];
@endphpphp

@section('content')
    <div class="products-wrap">
        <x-section-heading :title="$searching ? 'Search Results' : 'Featured Products'" />

        <div class="product-grid">
            @forelse ($products as $product)
                <x-product-card
                    :name="$product->name"
                    :price="number_format((float) $product->price, 2)"
                    :src="$product->imageUrl()"
                    :href="route('products.show', $product)" />
            @empty
                <p>No listings found.</p>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div style="display:flex;justify-content:center;gap:16px;margin:24px 0">
                @if ($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}">&larr; Previous</a>@endif
                <span>Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                @if ($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}">Next &rarr;</a>@endif
            </div>
        @endif

        <x-section-heading title="Popular Categories" />

        <div class="category-grid">
            @foreach ($categories as $category)
                <x-category-card :label="$category['label']" :image="$category['image']" />
            @endforeach
        </div>
    </div>
@endsection
