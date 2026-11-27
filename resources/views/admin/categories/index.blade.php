@extends('layouts.admin')

@section('title', 'Categories')
@section('heading', 'Manage Categories')

@section('content')
<section class="table-card pad">
    <div class="toolbar">
        <div></div>
        <a class="btn-blue" href="{{ route('admin.categories.create') }}">Add Category</a>
    </div>

    <table class="lst">
        <thead>
            <tr><th>No.</th><th style="text-align:left">Category</th><th>Type</th><th>Listings</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse ($categories as $i => $category)
                <tr>
                    <td class="no"><span></span>{{ $categories->firstItem() + $i }}</td>
                    <td class="prod"><div><span>{{ $category->name }}</span></div></td>
                    <td>{{ \App\Models\Listing::TYPES[$category->type]['label'] ?? ucfirst($category->type) }}</td>
                    <td>{{ $category->listings_count }}</td>
                    <td>
                        <span class="acts">
                            <a href="{{ route('admin.categories.edit', $category) }}" aria-label="Edit"><x-admin.icon name="edit" :size="24" :stroke="1.5" /></a>
                            <form method="post" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" aria-label="Delete"><x-admin.icon name="trash" :size="24" :stroke="1.5" /></button>
                            </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <x-admin.pagination :paginator="$categories" />
</section>
@endsection
