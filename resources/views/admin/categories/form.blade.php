@extends('layouts.admin')

@php $editing = $category->exists; @endphp

@section('title', 'Categories')
@section('heading', $editing ? 'Edit Category' : 'Add Category')

@section('content')
<section class="panel">
    <h2>Category</h2>

    <form method="post" action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        @include('partials.errors')

        <div class="grid-3 two">
            <div class="fld">
                <label for="name">Name</label>
                <input class="in" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="255">
                @error('name')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div class="fld">
                <label for="type">Type</label>
                <select class="in" id="type" name="type">
                    @foreach (\App\Models\Listing::TYPES as $key => $type)
                        <option value="{{ $key }}" @selected(old('type', $category->type) === $key)>{{ $type['label'] }}</option>
                    @endforeach
                </select>
                @error('type')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="actions">
            <button class="btn-blue" type="submit">{{ $editing ? 'Update' : 'Create' }}</button>
            <a class="cancel" href="{{ route('admin.categories') }}">Cancel</a>
        </div>
    </form>
</section>
@endsection
