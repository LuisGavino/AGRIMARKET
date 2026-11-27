@extends('layouts.app')

@section('title', 'Order #'.$order->id.' – AgriMarket')

@section('content')
    <div class="seller">
        <div class="seller__body" style="padding:32px">
            <h1 style="text-align:left;font-weight:500;font-size:22px;margin-bottom:22px">Order #{{ $order->id }}</h1>

            <section class="card" style="margin-bottom:14px">
                <p>Status: <strong>{{ ucfirst($order->status) }}</strong> &middot; Placed {{ $order->created_at->format('d-m-Y') }}</p>
                <ul style="margin-top:12px;line-height:1.8">
                    @foreach ($order->items as $item)
                        <li>{{ $item->name }} &times; {{ $item->quantity }} &mdash; {{ number_format((float) $item->unit_price * $item->quantity, 2) }}</li>
                    @endforeach
                </ul>
                <p style="margin-top:12px"><strong>Total: {{ number_format((float) $order->total, 2) }}</strong></p>
            </section>

            <a href="{{ route('orders.index') }}">&larr; Back to my orders</a>
        </div>
    </div>
@endsection
