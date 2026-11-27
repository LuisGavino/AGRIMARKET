@extends('layouts.app')

@section('title', 'Check Out – AgriMarket')

@php
    $order = [
        ['name' => 'Watermelon Seeds', 'image' => 'checkout-watermelon-seeds.png', 'price' => 150],
        ['name' => 'Cows',             'image' => 'checkout-cows.png',             'price' => 150],
        ['name' => 'Apple',            'image' => 'checkout-apple.png',            'price' => 150],
    ];
@endphp

@section('content')
    <div class="checkout">
        {{-- Left: order summary + payment method --}}
        <section class="checkout__left">
            <h2>Summary of your orders</h2>
            <p class="hint">Check your item and select method of shipping for better experience order of item</p>

            <div class="order-box">
                @foreach ($order as $item)
                    <div class="order-item">
                        <img src="{{ asset('images/' . $item['image']) }}" alt="{{ $item['name'] }}">
                        <div>
                            <strong>{{ $item['name'] }}</strong>
                            <span>₱ {{ $item['price'] }}</span>
                        </div>
                        <button type="button" class="trash" aria-label="Remove {{ $item['name'] }}"><x-icon name="trash" :size="18" /></button>
                    </div>
                @endforeach
            </div>

            <div class="pay-title">Select Payment <x-icon name="alert-circle" :size="18" /></div>

            <label class="pay-option is-selected">
                <img src="{{ asset('images/icons/cash-on-delivery.png') }}" alt="">
                <span class="pay-option__name">Cash on Delivery</span>
                <span class="pay-option__tag">Free<br>delivery</span>
                <small>Delivery in 2-3 working days</small>
            </label>

            <label class="pay-option">
                <img src="{{ asset('images/icons/gcash.png') }}" alt="">
                <span class="pay-option__name">Pay using Gcash</span>
                <span class="pay-option__amount">₱ 40,680</span>
                <small>Delivery in 2-3 working days</small>
            </label>
        </section>

        {{-- Right: payment details --}}
        <section class="checkout__right">
            <h2>Payment details</h2>
            <p class="hint">Complete your purchase item by providing your new payment details order</p>

            <form class="checkout__form" action="#" method="post" onsubmit="return false;">
                <div>
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Last Name, First Name, Middle Initial (Optional)">
                </div>
                <div>
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="House No. 123, Street Name, City, Province">
                </div>

                <div class="checkout__grid">
                    <div>
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="09123456789">
                    </div>
                    <div>
                        <label for="message">Message to Seller</label>
                        <input type="text" id="message" name="message">
                    </div>
                    <div>
                        <label for="receipt">Request E-Receipt</label>
                        <input type="checkbox" id="receipt" name="receipt" class="checkout__receipt">
                    </div>
                    <div style="align-self:end">
                        <button type="button" class="btn btn--blue-soft" style="width:100%;height:56px;font-size:18px">Change Address</button>
                    </div>
                </div>

                <div class="checkout__summary">
                    <div><span>Subtotal</span><span>₱ 40,530</span></div>
                    <div><span>Shipping Fee</span><span>₱ 150</span></div>
                    <div class="total"><span>Total</span><span>₱ 40,680</span></div>
                </div>

                <button type="submit" class="btn btn--blue-soft checkout__pay">Pay ₱ 40,680</button>
            </form>
        </section>
    </div>
@endsection
