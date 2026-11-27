{{-- /search – the Home page with the search overlay already open (matches the "Search Overlay" frame). --}}
@extends('layouts.app')

@section('title', 'Search – AgriMarket')
@section('search_open', '1')

@section('content')
    @include('app._home-content')
@endsection
