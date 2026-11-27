@extends('layouts.public')

@section('title', 'About – AgriMarket')
@section('body_class', 'public--gradient')

@section('content')
    <section class="about">
        <img src="{{ asset('images/about-basket.png') }}" alt="Basket of fresh farm produce" class="about__image">

        <div>
            <p class="about__eyebrow">Welcome to AgriCONNECT</p>
            <h1 class="about__title">Fresh &amp; Healthy<br>FARM PRODUCTS</h1>

            <a href="{{ route('products') }}" class="btn btn--green">Shop now <x-icon name="arrow-right" :size="16" /></a>

            <div class="about__dots" aria-hidden="true">
                <span></span><span class="is-active"></span><span></span>
            </div>
        </div>
    </section>
@endsection
