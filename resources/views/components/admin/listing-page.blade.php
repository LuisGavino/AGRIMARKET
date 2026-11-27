{{-- Shared body for Produce / Livestock / Service Providers pages (database-backed).
     $items is a paginator of App\Models\Listing. --}}
@props(['items', 'config', 'filter' => null, 'search' => '', 'counts'])

@php $base = $config['route']; @endphp

<div class="stats-3 narrow">
    <x-admin.stat-card title="Total Listings" :value="number_format($counts['week'])" caption="Last 7 days" />
    <x-admin.stat-card title="New Listings" :value="number_format($counts['today'])" caption="Today" />
    <x-admin.stat-card title="Total Listings" :value="number_format($counts['all'])" caption="All Time" />
</div>

<section class="table-card pad">
    <div class="toolbar">
        <div class="pill-tabs">
            <a href="{{ route($base) }}" @class(['on' => ! $filter])>All Product <em>({{ $counts['all'] }})</em></a>
            <a href="{{ route($base, ['filter' => 'featured']) }}" @class(['on' => $filter === 'featured'])>Featured Products</a>
            <a href="{{ route($base, ['filter' => 'inactive']) }}" @class(['on' => $filter === 'inactive'])>Inactive / Sold</a>
            <a href="{{ route($base, ['filter' => 'out-of-stock']) }}" @class(['on' => $filter === 'out-of-stock'])>Out of Stock</a>
        </div>
        <div class="tools-r">
            <form method="get" action="{{ route($base) }}">
                @if ($filter)<input type="hidden" name="filter" value="{{ $filter }}">@endif
                <label class="search-box">
                    <input type="search" name="q" value="{{ $search }}" placeholder="Search Product">
                    <x-admin.icon name="search" :size="22" />
                </label>
                <button class="filter-btn" type="submit" aria-label="Search"><x-admin.icon name="filter" :size="20" /></button>
            </form>
            <a class="btn-blue" href="{{ route($base.'.create') }}">Add Listing</a>
        </div>
    </div>

    <table class="lst">
        <thead>
            <tr><th>No.</th><th style="text-align:left">Product</th><th>Listing Date</th><th>Stock</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $i => $item)
                <tr>
                    <td class="no"><span></span>{{ $items->firstItem() + $i }}</td>
                    <td class="prod">
                        <div>
                            <img src="{{ $item->imageUrl() }}" alt="">
                            <span><a href="{{ route($base.'.show', $item) }}">{{ $item->name }}</a></span>
                        </div>
                    </td>
                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        <span class="acts">
                            <a href="{{ route($base.'.edit', $item) }}" aria-label="Edit"><x-admin.icon name="edit" :size="24" :stroke="1.5" /></a>
                            <form method="post" action="{{ route($base.'.destroy', $item) }}" onsubmit="return confirm('Delete this listing? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" aria-label="Delete"><x-admin.icon name="trash" :size="24" :stroke="1.5" /></button>
                            </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No listings found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <x-admin.pagination :paginator="$items" />
</section>
