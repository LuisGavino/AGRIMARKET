@extends('layouts.admin')

@section('title', 'Orders')
@section('heading', 'Manage Orders')

@section('content')
<section class="table-card pad">
    <div class="toolbar">
        <div class="pill-tabs">
            <a href="{{ route('admin.orders') }}" @class(['on' => ! $status])>All Orders</a>
            @foreach (array_keys(\App\Models\Order::TRANSITIONS) as $s)
                <a href="{{ route('admin.orders', ['status' => $s]) }}" @class(['on' => $status === $s])>{{ ucfirst($s) }}</a>
            @endforeach
        </div>
    </div>

    <table class="lst">
        <thead>
            <tr><th>Order</th><th style="text-align:left">Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="no"><span></span>#{{ $order->id }}</td>
                    <td class="prod"><div><span>{{ $order->user?->name }}</span></div></td>
                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td>{{ number_format((float) $order->total, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td><span class="acts"><a href="{{ route('admin.orders.show', $order) }}" aria-label="View"><x-admin.icon name="eye" :size="24" :stroke="1.5" /></a></span></td>
                </tr>
            @empty
                <tr><td colspan="7">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <x-admin.pagination :paginator="$orders" />
</section>
@endsection
