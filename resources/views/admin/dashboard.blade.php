@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Admin Dashboard')

@php
    $stats = [
        ['Total Listings', '420', '128%'], ['Total Orders', '79', '43%'], ['Registered Users', '312', '64%'],
        ['Livestock Listed', '67', '65%'], ['Services Listed', '32', '35%'], ['Produce Listed', '240', '85%'],
    ];
    $bars = [38,10,32,28,10,36,8,22,34,10,28,34,22,30,16,32,10,38,24,34,12,40,26,36,14,42,20,38];
    $topProducts = [
        ['Cows', 'cows'], ['Pest Control', 'pest-control'], ['Apple', 'apple'],
        ['Veterinary Care', 'veterinary'], ['Watermelon Seeds', 'watermelon-seeds'],
    ];
    // Chart geometry (SVG units) – 0k..50k on the y-axis, Sun..Sat on the x-axis.
    $line = '0,199 67,199 152,140 267,140 362,182 439,182 497,97 637,97 692,167 792,167 817,123 884,123';
@endphp

@section('content')
<div class="dash">
    <div class="col-l">
        <div class="stats-3" style="margin-top:0">
            @foreach ($stats as [$title, $value, $delta])
                <x-admin.stat-card :title="$title" :value="$value" :delta="$delta" caption="Last 7 days" />
            @endforeach
        </div>

        <section class="card report">
            <div class="head">
                <h3>Report for this week</h3>
                <div style="display:flex;align-items:center;gap:14px">
                    <div class="seg"><button class="on" type="button">This week</button><button type="button">Last week</button></div>
                    <x-admin.icon name="dots" :size="22" />
                </div>
            </div>

            <div class="tabs-k">
                <div class="on"><b>52k</b><span>Total Sales</span></div>
                <div><b>3.5k</b><span>Total Listings</span></div>
                <div><b>2.5k</b><span>Total Users</span></div>
                <div><b>0.5k</b><span>Total Orders</span></div>
            </div>

            <div class="chart">
                <div class="y-axis">
                    @foreach (['50k','40k','30k','20k','10k','0k'] as $label)<span>{{ $label }}</span>@endforeach
                </div>
                <div class="plot">
                    <svg viewBox="0 0 884 292" preserveAspectRatio="none" role="img" aria-label="Weekly sales chart">
                        <defs>
                            <linearGradient id="area" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#7cc99a" stop-opacity=".55"/>
                                <stop offset="1" stop-color="#7cc99a" stop-opacity=".05"/>
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $line }} 884,292 0,292" fill="url(#area)"/>
                        <polyline points="{{ $line }}" fill="none" stroke="#5db97f" stroke-width="2" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                        <line x1="439" y1="172" x2="439" y2="292" stroke="#7cc99a" stroke-dasharray="4 4" vector-effect="non-scaling-stroke"/>
                    </svg>
                    <span class="dot" style="left:49.66%;top:58.9%"></span>
                    <div class="tip" style="left:49.66%;top:28%">Thursday<br>14k</div>
                    <div class="x-axis">
                        @foreach (['Sun' => 1.7, 'Mon' => 17.6, 'Tue' => 33.6, 'Wed' => 49.66, 'Thu' => 66, 'Fri' => 81.3, 'Sat' => 96.4] as $day => $x)
                            <span style="left:{{ $x }}%" @class(['bold' => $day === 'Wed'])>{{ $day }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-r" style="display:flex;flex-direction:column;gap:20px">
        <section class="card sales">
            <div class="top" style="display:flex;justify-content:space-between;align-items:center">
                <h3>Total Sales</h3>
                <x-admin.icon name="dots" :size="22" />
            </div>
            <div class="big">₱ 123,000 <small>43%</small></div>
            <div class="cap">Last 7 days</div>
            <div class="bars">
                @foreach ($bars as $h)<i style="height:{{ $h + 10 }}px"></i>@endforeach
            </div>
            <a class="btn-insight" href="#">View Insight</a>
        </section>

        <section class="card top-products">
            <div class="head"><h3>Top Products</h3><a href="#">All product</a></div>
            <label class="mini-search"><x-admin.icon name="search" :size="20" /><input type="search" placeholder="Search"></label>
            @foreach ($topProducts as [$name, $img])
                <div class="tp">
                    <img src="{{ asset('images/products/'.$img.'.jpg') }}" alt="">
                    <div><b>{{ $name }}</b><small>Item: #FXZ-4567</small></div>
                    <span class="price">PHP 123</span>
                </div>
            @endforeach
        </section>
    </div>
</div>
@endsection
