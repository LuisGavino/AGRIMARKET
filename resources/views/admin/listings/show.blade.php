@extends('layouts.admin')

@section('title', $config['label'])
@section('heading', $listing->name)

@section('content')
<section class="panel">
    <div class="row-h">
        <h2>{{ $config['label'] }} Details</h2>
        <a class="btn-line" href="{{ route($config['route'].'.edit', $listing) }}">Edit <x-admin.icon name="edit" :size="22" :stroke="1.5" /></a>
    </div>

    <img class="thumb" src="{{ $listing->imageUrl() }}" alt="{{ $listing->name }}" style="margin-bottom:16px">

    <div class="grid-3">
        <div class="fld"><label>Category</label><div class="in ro">{{ $listing->category?->name ?? '—' }}</div></div>
        <div class="fld"><label>Price</label><div class="in ro">{{ number_format((float) $listing->price, 2) }}</div></div>
        <div class="fld"><label>Quantity</label><div class="in ro">{{ $listing->quantity }}</div></div>
        <div class="fld"><label>Status</label><div class="in ro">{{ ucfirst($listing->status) }}</div></div>
        <div class="fld"><label>Seller</label><div class="in ro">{{ $listing->seller?->name ?? '—' }}</div></div>
        <div class="fld"><label>Location</label><div class="in ro">{{ $listing->location ?? '—' }}</div></div>
        @foreach ($config['fields'] as $field => $label)
            <div class="fld"><label>{{ $label }}</label><div class="in ro">{{ $field === 'harvest_date' ? ($listing->harvest_date?->format('d-m-Y') ?? '—') : ($listing->{$field} ?? '—') }}</div></div>
        @endforeach
    </div>
    @if ($listing->description)
        <div class="fld" style="margin-top:16px"><label>Description</label><div class="in ro" style="height:auto;min-height:51px;padding-block:8px;line-height:1.3">{{ $listing->description }}</div></div>
    @endif
    <div class="actions"><a class="cancel" href="{{ route($config['route']) }}">Back</a></div>
</section>
@endsection
