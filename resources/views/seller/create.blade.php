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
            'fields' => [['Unit', 'Input kg, sack, piece, etc.', 'unit', 'text'], ['Harvest Date', 'Input Harvest Date', 'harvest_date', 'date']],
        ],
        'livestock' => [
            'title'  => 'Product - Livestock',
            'fields' => [['Weight', 'Input Weight', 'weight', 'number'], ['Purpose', 'Input Purpose', 'purpose', 'text']],
        ],
        'service' => [
            'title'  => 'Product - Service',
            // TODO: the Service frame was not captured in Figma before the API limit was hit.
            // These two field labels are placeholders – replace with the labels from the design.
            'fields' => [['Service Area', 'Input service area', 'service_area', 'text'], ['Availability', 'Input availability', 'availability', 'text']],
        ],
    ][$type];
@endphp

@section('content')
    <div class="seller">
        <div class="seller__body" style="padding:32px">
            <h1 style="text-align:left;font-weight:500;font-size:22px;margin-bottom:22px">{{ $config['title'] }}</h1>
            @if ($errors->any())
                <div class="flash" role="alert" style="max-width:1100px">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('seller.store', $type) }}" method="post" enctype="multipart/form-data" style="display:grid;grid-template-columns:600px 1fr;gap:22px;align-items:start;max-width:1100px">
                @csrf
                {{-- Product information --}}
                <section class="card">
                    <h2>Product Information</h2>
                    <p class="card__desc">Lorem ipsum dolor sit amet consectetur. Non ac nulla aliquam aenean in velit mattis.</p>

                    <div class="form-stack" style="margin-top:18px">
                        <x-field label="Description" name="description" placeholder="Input Description" :value="old('description')" />
                        <x-field label="Product Name" name="name" placeholder="Input product name" :value="old('name')" />

                        <div class="form-row form-row--2">
                            @foreach ($config['fields'] as [$label, $placeholder, $column, $inputType])
                                <x-field :label="$label" :name="$column" :type="$inputType" :placeholder="$placeholder" :value="old($column)" />
                            @endforeach
                        </div>

                        <div class="form-row form-row--2">
                            <div class="field">
                                <label for="category_id">Product Category</label>
                                <select id="category_id" name="category_id">
                                    <option value="">Select product category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-field label="Price" name="price" type="number" placeholder="Input Price" :value="old('price')" />
                        </div>

                        <x-field label="Quantity" name="quantity" type="number" placeholder="Input stock" :value="old('quantity')" />
                        <x-field label="Location" name="location" placeholder="Input location" :value="old('location')" />
                    </div>

                    <div style="height:84px"></div>
                </section>

                {{-- Image upload --}}
                <div>
                    <section class="card">
                        <h2>Image Product</h2>
                        <p style="margin-top:8px;font-size:9px"><strong style="color:var(--blue)">Note :</strong> Format photos PNG, JPG, or WebP (Max size 4mb)</p>

                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:20px">
                            @foreach (range(1, 4) as $n)
                                <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;height:97px;border:1px dashed var(--blue);border-radius:6px;background:#eef5ff;color:var(--blue);font-size:10px;cursor:pointer">
                                    <x-icon name="image" :size="26" />
                                    Photo {{ $n }}
                                    <input type="file" name="photos[]" accept=".png,.jpg,.jpeg,.webp" hidden>
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
