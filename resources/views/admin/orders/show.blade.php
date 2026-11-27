@extends('layouts.admin')

@section('title', 'Orders')
@section('heading', 'Order #'.$order->id)

@section('content')
<section class="panel">
    <h2>Order Details</h2>
    <div class="grid-3">
        <div class="fld"><label>Customer</label><div class="in ro">{{ $order->user?->name }}</div></div>
        <div class="fld"><label>Email</label><div class="in ro">{{ $order->user?->email }}</div></div>
        <div class="fld"><label>Status</label><div class="in ro">{{ ucfirst($order->status) }}</div></div>
        <div class="fld"><label>Placed</label><div class="in ro">{{ $order->created_at->format('d-m-Y H:i') }}</div></div>
        <div class="fld"><label>Total</label><div class="in ro">{{ number_format((float) $order->total, 2) }}</div></div>
    </div>
</section>

<section class="panel">
    <h2>Items</h2>
    <table class="tbl">
        <thead><tr><th>Product</th><th>Unit Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

<section class="panel">
    <h2>Update Status</h2>
    @if ($order->allowedNextStatuses())
        <form method="post" action="{{ route('admin.orders.status', $order) }}" style="display:flex;gap:16px;align-items:center">
            @csrf @method('PATCH')
            <div class="fld" style="min-width:240px">
                <select class="in" name="status" aria-label="New status">
                    @foreach ($order->allowedNextStatuses() as $next)
                        <option value="{{ $next }}">{{ ucfirst($next) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-blue" type="submit">Update</button>
        </form>
    @else
        <p>This order is {{ $order->status }} and its status can no longer be changed.</p>
    @endif
</section>

<section class="panel">
    <h2>Status History</h2>
    <table class="tbl">
        <thead><tr><th>When</th><th>From</th><th>To</th><th>Changed by</th></tr></thead>
        <tbody>
            @forelse ($order->histories as $history)
                <tr>
                    <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $history->from_status ? ucfirst($history->from_status) : '—' }}</td>
                    <td>{{ ucfirst($history->to_status) }}</td>
                    <td>{{ $history->changedBy?->name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No status changes yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
<div class="actions"><a class="cancel" href="{{ route('admin.orders') }}">Back to orders</a></div>
@endsection
