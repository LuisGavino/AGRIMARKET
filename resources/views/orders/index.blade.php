@extends('layouts.app')

@section('title', 'My Orders – AgriMarket')

@section('content')
    <div class="seller">
        <div class="seller__body" style="padding:32px">
            <h1 style="text-align:left;font-weight:500;font-size:22px;margin-bottom:22px">My Orders</h1>

            @forelse ($orders as $order)
                <article class="card" style="margin-bottom:14px">
                    <a href="{{ route('orders.show', $order) }}"><strong>Order #{{ $order->id }}</strong></a>
                    &middot; {{ $order->created_at->format('d-m-Y') }}
                    &middot; {{ $order->items_count }} item(s)
                    &middot; {{ number_format((float) $order->total, 2) }}
                    &middot; {{ ucfirst($order->status) }}
                </article>
            @empty
                <p>You have no orders yet.</p>
            @endforelse

            {{ $orders->links() }}
        </div>
    </div>
@endsection
