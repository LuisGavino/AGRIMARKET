@extends('layouts.seller')

@section('title', 'Active Listings – AgriMarket')



@section('seller_content')
    <h1>Active Listings</h1>

    @forelse ($listings as $listing)
        <article class="listing">
            <img src="{{ $listing->imageUrl() }}" alt="{{ $listing->name }}" class="listing__img">

            <div class="listing__body">
                <h2 class="listing__title">{{ $listing->name }}</h2>
                <div class="listing__price">PHP {{ number_format((float) $listing->price, 2) }}</div>
                <div class="listing__meta">
                    {{ ucfirst($listing->status) }} Listing - Listed on {{ $listing->created_at->format('n/j/Y') }}<br>
                    Listed on {{ $listing->category?->name ?? 'Uncategorized' }} Category<br>
                    {{ $listing->location }}
                </div>

                <div class="listing__actions">
                    @if ($listing->status === 'active')
                        <form method="post" action="{{ route('seller.sold', $listing) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn--gray"><x-icon name="circle-check" :size="18" /> Mark as sold</button>
                        </form>
                    @endif
                    <button type="button" class="btn btn--gray"><x-icon name="trending-up" :size="18" /> Advertise</button>
                    <button type="button" class="btn btn--gray"><x-icon name="share" :size="18" /> Share</button>
                </div>
            </div>
        </article>
    @empty
        <p>You have no listings yet.</p>
    @endforelse
@endsection
