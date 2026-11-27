{{-- Shared body for Produce / Livestock / Service Providers pages.
     $items = [['name' => ..., 'image' => 'products/xyz.jpg', 'date' => ..., 'listings' => ...], ...] --}}
@props(['items'])

<div class="stats-3 narrow">
    <x-admin.stat-card title="Total Listings" value="312" delta="↑64%" caption="Last 7 days" />
    <x-admin.stat-card title="New Listings" value="3" delta="↑14.4%" caption="Today" />
    <x-admin.stat-card title="Total Listings" value="11,040" delta="↑14.4%" caption="All Time" />
</div>

<section class="table-card pad">
    <div class="toolbar">
        <div class="pill-tabs">
            <a href="#" class="on">All Product <em>(145)</em></a>
            <a href="#">Featured Products</a>
            <a href="#">On Sale</a>
            <a href="#">Out of Stock</a>
        </div>
        <div class="tools-r">
            <label class="search-box">
                <input type="search" placeholder="Search Product">
                <x-admin.icon name="search" :size="22" />
            </label>
            <button class="filter-btn" type="button" aria-label="Filter"><x-admin.icon name="filter" :size="20" /></button>
        </div>
    </div>

    <table class="lst">
        <thead>
            <tr><th>No.</th><th style="text-align:left">Product</th><th>Listing Date</th><th>Listings</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach ($items as $i => $item)
                <tr>
                    <td class="no"><span></span>{{ $i + 1 }}</td>
                    <td class="prod">
                        <div>
                            <img src="{{ asset('images/'.$item['image']) }}" alt="">
                            <span>{{ $item['name'] }}</span>
                        </div>
                    </td>
                    <td>{{ $item['date'] ?? '01-01-2025' }}</td>
                    <td>{{ $item['listings'] }}</td>
                    <td>
                        <span class="acts">
                            <a href="#" aria-label="Edit"><x-admin.icon name="edit" :size="24" :stroke="1.5" /></a>
                            <a href="#" aria-label="Delete"><x-admin.icon name="trash" :size="24" :stroke="1.5" /></a>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-admin.pagination />
</section>
