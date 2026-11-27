@extends('layouts.app')

@section('title', 'My Wishlist – AgriMarket')

@php
    // Static sample rows (front-end only).
    $items = [
        ['name' => 'Pest Control', 'image' => 'wishlist-pest-control.jpg', 'price' => 500],
        ['name' => 'Cows',         'image' => 'wishlist-cows.jpg',         'price' => 40000],
        ['name' => 'Apple',        'image' => 'wishlist-apple.png',        'price' => 30],
    ];
    $subtotal = collect($items)->sum('price');
    $shipping = 350;
@endphp

@section('content')
    <div class="wishlist">
        <h1>My Wishlist</h1>

        <table>
            <thead>
                <tr>
                    <th>Image</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><img src="{{ asset('images/' . $item['image']) }}" alt="{{ $item['name'] }}"></td>
                        <td>{{ $item['name'] }}</td>
                        <td>₱ {{ number_format($item['price']) }}</td>
                        <td>
                            <span class="qty" data-qty>
                                <button type="button" data-qty-step="-1" aria-label="Decrease">-</button>
                                <span data-qty-value>1</span>
                                <button type="button" data-qty-step="1" aria-label="Increase">+</button>
                            </span>
                        </td>
                        <td>₱ {{ number_format($item['price']) }}</td>
                        <td><button type="button" class="trash" aria-label="Remove {{ $item['name'] }}"><x-icon name="trash" :size="16" /></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="wishlist__totals">
            <div class="row"><span>Sub Total</span><span>₱ {{ number_format($subtotal) }}</span></div>
            <div class="row"><span>Shipping</span><span>₱ {{ number_format($shipping) }}</span></div>
            <div class="row"><span>Total</span><span>₱ {{ number_format($subtotal + $shipping) }}</span></div>
            <a href="{{ route('checkout') }}" class="btn btn--red">Check Out</a>
        </div>
    </div>
@endsection
