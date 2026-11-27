@extends('layouts.public')

@section('title', $listing->name.' – AgriMarket')
@section('body_class', 'public--gradient')

@section('content')
    <div class="products-wrap">
        <x-section-heading :title="$listing->name" />

        <article class="card" style="display:grid;grid-template-columns:320px 1fr;gap:28px;align-items:start">
            <img src="{{ $listing->imageUrl() }}" alt="{{ $listing->name }}" style="width:100%;border-radius:12px;object-fit:cover">

            <div>
                <p style="font-size:24px;font-weight:600">{{ number_format((float) $listing->price, 2) }}</p>
                <p>{{ $listing->category?->name }} &middot; {{ \App\Models\Listing::TYPES[$listing->type]['label'] }}</p>
                @if ($listing->description)<p style="margin-top:12px">{{ $listing->description }}</p>@endif

                <ul style="margin-top:14px;line-height:1.8">
                    <li>In stock: {{ $listing->quantity }}</li>
                    @foreach (\App\Models\Listing::TYPES[$listing->type]['fields'] as $field => $label)
                        @php $value = $field === 'harvest_date' ? $listing->harvest_date?->format('d-m-Y') : $listing->{$field}; @endphp
                        @if ($value !== null && $value !== '')<li>{{ $label }}: {{ $value }}</li>@endif
                    @endforeach
                    @if ($listing->location)<li>Location: {{ $listing->location }}</li>@endif
                    @if ($listing->seller)<li>Provider: {{ $listing->seller->name }}</li>@endif
                </ul>
            </div>
        </article>
    </div>
@endsection
