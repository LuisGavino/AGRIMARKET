@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Manage Users')

@php
    $users = [
        ['#CUST001', 'John Doe', '+1234567890', 25, true],
        ['#CUST001', 'John Doe', '+1234567890', 25, true],
        ['#CUST001', 'John Doe', '+1234567890', 25, true],
        ['#CUST001', 'John Doe', '+1234567890', 25, true],
        ['#CUST001', 'Jane Smith', '+1234567890', 5, false],
        ['#CUST001', 'Emily Davis', '+1234567890', 30, true],
        ['#CUST001', 'Emily Davis', '+1234567890', 30, true],
        ['#CUST001', 'Emily Davis', '+1234567890', 30, true],
    ];
@endphp

@section('content')
<div class="stats-3 narrow">
    <x-admin.stat-card title="Total Customers" value="312" delta="↑64%" caption="Last 7 days" />
    <x-admin.stat-card title="New Customers" value="3" delta="↑14.4%" caption="Today" />
    <x-admin.stat-card title="Total Customers" value="11,040" delta="↑14.4%" caption="All Time" />
</div>

<section class="table-card">
    <table class="tbl users-tbl">
        <thead>
            <tr><th>Customer Id</th><th>Name</th><th>Phone</th><th>Order Count</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach ($users as [$id, $name, $phone, $orders, $active])
                <tr>
                    <td>{{ $id }}</td>
                    <td>{{ $name }}</td>
                    <td>{{ $phone }}</td>
                    <td>{{ $orders }}</td>
                    <td><span @class(['st', 'on' => $active, 'off' => ! $active])>{{ $active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <span class="acts">
                            <a href="#" aria-label="Message"><x-admin.icon name="message" :size="24" :stroke="1.5" /></a>
                            <a href="#" aria-label="Delete"><x-admin.icon name="trash" :size="24" :stroke="1.5" /></a>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:0 22px 14px"><x-admin.pagination /></div>
</section>
@endsection
