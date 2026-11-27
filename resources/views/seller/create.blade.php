@extends('layouts.app')

@section('title', 'Create Listing – AgriMarket')

@php
    /*
     * One view for the three "Create New Listing" frames (Produce / Livestock / Service).
     * They share the same layout; only the title and the two fields in the 3rd row differ.
     * $type is passed from routes/web.php.
     */
    $config = [
        'produce' => [
            'title'  => 'Product - Produce',
            'fields' => [['Unit', 'Input kg, sack, piece, etc.'], ['Harvest Date', 'Input Harvest Date']],
        ],
        'livestock' => [
            'title'  => 'Product - Livestock',
            'fields' => [['Weight', 'Input Weight'], ['Purpose', 'Input Purpose']],
        ],
        'service' => [
            'title'  => 'Product - Service',
            // TODO: the Service frame was not captured in Figma before the API limit was hit.
            // These two field labels are placeholders – replace with the labels from the design.
            'fields' => [['Service Area', 'Input service area'], ['Availability', 'Input availability']],
        ],
    ][$type];
@endphp

@section('content')
    <div class="seller">
        <div class="seller__body" style="padding:32px">
            <h1 style="text-align:left;font-weight:500;font-size:22px;margin-bottom:22px">{{ $config['title'] }}</h1>

            <form action="#" method="post" onsubmit="return false;" style="display:grid;grid-template-columns:600px 1fr;gap:22px;align-items:start;max-width:1100px">
                {{-- Product information --}}
                <section class="card">
                    <h2>Product Information</h2>
                    <p class="card__desc">Lorem ipsum dolor sit amet consectetur. Non ac nulla aliquam aenean in velit mattis.</p>

                    <div class="form-stack" style="margin-top:18px">
                        <x-field label="Description" name="description" placeholder="Input Description" />
                        <x-field label="Product Name" name="product_name" placeholder="Input product name" />

                        <div class="form-row form-row--2">
                            <x-field :label="$config['fields'][0][0]" name="field_one" :placeholder="$config['fields'][0][1]" />
                            <x-field :label="$config['fields'][1][0]" name="field_two" :placeholder="$config['fields'][1][1]" />
                        </div>

                        <div class="form-row form-row--2">
                            <div class="field">
                                <label for="category">Product Category</label>
                                <select id="category" name="category">
                                    <option value="" selected disabled>Select product category</option>
                                    <option>Fruits &amp; Vegetables</option>
                                    <option>Seeds</option>
                                    <option>Livestock</option>
                                    <option>Poultry</option>
                                </select>
                            </div>
                            <x-field label="Price" name="price" placeholder="Input Price" />
                        </div>

                        <x-field label="Quantity" name="quantity" placeholder="Input stock" />
                    </div>

                    <div style="height:84px"></div>
                </section>

                {{-- Image upload --}}
                <div>
                    <section class="card">
                        <h2>Image Product</h2>
                        <p style="margin-top:8px;font-size:9px"><strong style="color:var(--blue)">Note :</strong> Format photos SVG, PNG, or JPG (Max size 4mb)</p>

                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:20px">
                            @foreach (range(1, 4) as $n)
                                <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;height:97px;border:1px dashed var(--blue);border-radius:6px;background:#eef5ff;color:var(--blue);font-size:10px;cursor:pointer">
                                    <x-icon name="image" :size="26" />
                                    Photo {{ $n }}
                                    <input type="file" name="photos[]" accept=".svg,.png,.jpg,.jpeg" hidden>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <div style="display:flex;justify-content:flex-end;margin-top:22px">
                        <button type="submit" class="btn btn--blue">Save Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
