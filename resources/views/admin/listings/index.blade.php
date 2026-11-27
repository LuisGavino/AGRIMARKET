@extends('layouts.admin')

@section('title', $config['label'])
@section('heading', 'Manage '.$config['label'].' Listings')

@section('content')
    <x-admin.listing-page :items="$items" :config="$config" :filter="$filter" :search="$search" :counts="$counts" />
@endsection
