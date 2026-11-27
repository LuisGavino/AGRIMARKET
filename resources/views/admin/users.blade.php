@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Manage Users')

@section('content')
<div class="stats-3 narrow">
    <x-admin.stat-card title="Total Users" :value="number_format($counts['week'])" caption="Last 7 days" />
    <x-admin.stat-card title="New Users" :value="number_format($counts['today'])" caption="Today" />
    <x-admin.stat-card title="Total Users" :value="number_format($counts['all'])" caption="All Time" />
</div>

<section class="table-card">
    <div class="toolbar" style="padding:18px 22px 0">
        <form method="get" action="{{ route('admin.users') }}" class="tools-r">
            <label class="search-box">
                <input type="search" name="q" value="{{ $search }}" placeholder="Search name or email">
                <x-admin.icon name="search" :size="22" />
            </label>
            <button class="filter-btn" type="submit" aria-label="Search"><x-admin.icon name="filter" :size="20" /></button>
        </form>
        <a class="btn-blue" href="{{ route('admin.users.create') }}">Add User</a>
    </div>

    <table class="tbl users-tbl">
        <thead>
            <tr><th>User Id</th><th>Name</th><th>Email</th><th>Order Count</th><th>Role</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>#{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->orders_count }}</td>
                    <td><span @class(['st', 'on' => $user->isAdmin(), 'off' => ! $user->isAdmin()])>{{ ucfirst($user->role) }}</span></td>
                    <td>
                        <span class="acts">
                            <a href="{{ route('admin.users.edit', $user) }}" aria-label="Edit"><x-admin.icon name="edit" :size="24" :stroke="1.5" /></a>
                            <form method="post" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" aria-label="Delete"><x-admin.icon name="trash" :size="24" :stroke="1.5" /></button>
                            </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:0 22px 14px"><x-admin.pagination :paginator="$users" /></div>
</section>
@endsection
