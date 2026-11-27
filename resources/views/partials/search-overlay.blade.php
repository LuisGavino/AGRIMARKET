{{-- Marketplace search / filter overlay. Opens from the sidebar search bar; Esc or × closes it.
     Pass ['open' => true] to render it already open. --}}
@php($open = $open ?? false)
<div id="search-overlay" @class(['search-overlay', 'is-open' => $open]) role="dialog" aria-label="Search AgriMarket">
    <div class="search-panel">
        <a href="#" class="search-panel__close" data-search-close aria-label="Close search">&times;</a>

        <form action="{{ route('home') }}" method="get" onsubmit="return false;">
            <div class="search-bar">
                <x-icon name="search" :size="20" />
                <input type="text" name="q" placeholder="Search for products, livestock, or services">
                <button type="submit" aria-label="Search"><x-icon name="arrow-right" :size="18" /></button>
            </div>

            <div class="filter">
                <div class="filter__head">
                    <div>
                        <h2>Marketplace Filter</h2>
                        <p>Refine local listings and services</p>
                    </div>
                    <a href="#">Reset all</a>
                </div>

                <div class="filter__section">
                    <div class="filter__label">Location</div>
                    <div class="select">
                        <span><x-icon name="map-pin" :size="16" /> <em>Any area</em></span>
                        <x-icon name="chevron-up" :size="16" />
                    </div>
                    <div class="select-options">
                        @foreach (['Butuan City', 'Cabadbaran', 'Bayugan', 'Surigao City', 'Other Areas'] as $place)
                            <div>{{ $place }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="filter__section">
                    <div class="filter__label">Category</div>
                    {{-- TODO: the Figma frame has 8 category checkboxes (2 columns x 4); only the first 4 labels were readable. --}}
                    <div class="checks">
                        <label class="check"><input type="checkbox" name="category[]" value="agriculture" checked> Agriculture</label>
                        <label class="check"><input type="checkbox" name="category[]" value="livestock" checked> Livestock</label>
                        <label class="check"><input type="checkbox" name="category[]" value="seeds-crops"> Seeds &amp; Crops</label>
                        <label class="check"><input type="checkbox" name="category[]" value="farm-supplies"> Farm Supplies</label>
                    </div>
                </div>

                <div class="filter__section">
                    <div class="filter__label">Price range</div>
                    {{-- TODO: confirm placeholder wording in Figma (node 98:1607) --}}
                    <div class="price-inputs">
                        <label><input type="number" name="min_price" placeholder="Min price"><span>₱</span></label>
                        <label><input type="number" name="max_price" placeholder="Max price"><span>₱</span></label>
                    </div>
                </div>

                <div class="filter__section">
                    <div class="filter__label">Service type</div>
                    {{-- TODO: the Figma frame has 7 pills; labels below are the 5 service categories from the Home page --}}
                    <div class="pills">
                        @foreach (['Veterinary Care' => true, 'Equipment & Machinery Rental' => false, 'Pest & Disease Control' => false, 'Crop Production & Farm Labor' => true, 'Transportation & Hauling' => false] as $label => $selected)
                            <button type="button" @class(['pill', 'is-selected' => $selected]) data-pill>{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="filter__section">
                    <div class="filter__label">Availability</div>
                    {{-- TODO: 4 checkboxes in Figma (node 98:1635); labels not readable, placeholders used --}}
                    <div class="checks">
                        <label class="check"><input type="checkbox" name="availability[]" value="available" checked> Available now</label>
                        <label class="check"><input type="checkbox" name="availability[]" value="delivery" checked> Delivery available</label>
                        <label class="check"><input type="checkbox" name="availability[]" value="pickup"> Pickup available</label>
                        <label class="check"><input type="checkbox" name="availability[]" value="negotiable"> Negotiable</label>
                    </div>
                </div>

                <button type="submit" class="filter__apply">Apply search <x-icon name="arrow-right" :size="16" /></button>
            </div>
        </form>
    </div>
</div>
