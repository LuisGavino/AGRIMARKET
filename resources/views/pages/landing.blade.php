@extends('layouts.public')

@section('title', 'AgriMarket Hub')

@section('content')
    <section class="hero">
        <img src="{{ asset('images/landing-sparkle.svg') }}" alt="" class="hero__spark">

        <div class="hero__content">
            <p class="hero__eyebrow">Welcome to Agricultural Products Rural Entrepreneurship Management System.</p>
            <h1 class="hero__title">Agrimarket Hub</h1>
            <p class="hero__lead">Empowering Rural Dreams, Nurturing Agricultural Growth – AgriMarket Hub cultivates prosperity from the roots up.</p>
        </div>
    </section>
@endsection
