@extends('layouts.admin')

@php
    $editing = $listing->exists;
    $base = $config['route'];
    $action = $editing ? route($base.'.update', $listing) : route($base.'.store');
@endphp

@section('title', $config['label'])
@section('heading', ($editing ? 'Edit ' : 'Add ').$config['label'].' Listing')

@section('content')
<section class="panel">
    <h2>Listing Information</h2>

    <form method="post" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        @include('partials.errors')

        <div class="grid-3">
            <div class="fld">
                <label for="name">Name</label>
                <input class="in" id="name" name="name" value="{{ old('name', $listing->name) }}" required maxlength="255">
                @error('name')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="category_id">Category</label>
                <select class="in" id="category_id" name="category_id">
                    <option value="">— None —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $listing->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="status">Status</label>
                <select class="in" id="status" name="status">
                    @foreach (\App\Models\Listing::STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('status', $listing->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid-3" style="margin-top:16px">
            <div class="fld">
                <label for="price">Price</label>
                <input class="in" id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $listing->price) }}" required>
                @error('price')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="quantity">Quantity</label>
                <input class="in" id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity', $listing->quantity ?? 0) }}" required>
                @error('quantity')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="location">Location</label>
                <input class="in" id="location" name="location" value="{{ old('location', $listing->location) }}" maxlength="255">
                @error('location')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid-3" style="margin-top:16px">
            @foreach ($config['fields'] as $field => $label)
                @php $value = old($field, $field === 'harvest_date' ? $listing->harvest_date?->format('Y-m-d') : $listing->{$field}); @endphp
                <div class="fld">
                    <label for="{{ $field }}">{{ $label }}</label>
                    <input class="in" id="{{ $field }}" name="{{ $field }}"
                           type="{{ $field === 'harvest_date' ? 'date' : ($field === 'weight' ? 'number' : 'text') }}"
                           @if ($field === 'weight') step="0.01" min="0" @endif
                           value="{{ $value }}">
                    @error($field)<div class="err">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </div>

        <div class="fld" style="margin-top:16px">
            <label for="description">Description</label>
            <textarea class="in" id="description" name="description" maxlength="5000">{{ old('description', $listing->description) }}</textarea>
            @error('description')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="grid-3" style="margin-top:16px">
            <div class="fld">
                <label for="image">Image (JPG, PNG or WebP, max 4 MB)</label>
                @if ($editing)
                    <img class="thumb" src="{{ $listing->imageUrl() }}" alt="" style="margin-bottom:10px">
                @endif
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
                @error('image')<div class="err">{{ $message }}</div>@enderror
                @if ($editing && $listing->image_path)
                    <label class="chk" style="margin-top:8px"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                @endif
            </div>
            <div class="fld">
                <label class="chk" style="margin-top:30px">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $listing->is_featured))> Featured product
                </label>
            </div>
        </div>

        <div class="actions">
            <button class="btn-blue" type="submit">{{ $editing ? 'Update' : 'Create' }}</button>
            <a class="cancel" href="{{ route($base) }}">Cancel</a>
        </div>
    </form>
</section>
@endsection
