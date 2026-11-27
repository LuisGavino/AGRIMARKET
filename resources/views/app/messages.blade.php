@extends('layouts.app')

@section('title', 'Messages – AgriMarket')

@section('content')
    <div class="chat">
        {{-- Conversation list --}}
        <section class="chat__list" aria-label="Conversations">
            <button type="button" class="chat__new"><x-icon name="search" :size="16" /> Search Chats</button>

            <div class="chat__item">
                <img src="{{ asset('images/avatar-nana.jpg') }}" alt="">
                <div style="flex:1;min-width:0">
                    <div class="chat__item-name"><span>Nana</span> <small>12:36 PM</small></div>
                    <div class="chat__item-msg">Yes, Still I…</div>
                </div>
                <x-icon name="more" :size="14" />
            </div>
        </section>

        {{-- Active conversation --}}
        <section class="chat__main" aria-label="Conversation with Nana">
            <header class="chat__head">
                <div class="chat__head-title">
                    <img src="{{ asset('images/product-corn-seeds-thumb.jpg') }}" alt="">
                    Nana - Corn Seeds
                </div>
                <div class="chat__head-actions">
                    <x-icon name="bell" :size="18" />
                    <x-icon name="more" :size="18" />
                </div>
            </header>

            <div class="chat__stream">
                <p class="chat__system">Nana Started this chat •</p>
                <p class="chat__system">Thursday,Jan 4 • 6:21 PM</p>

                <div class="chat__row">
                    <img src="{{ asset('images/avatar-nana.jpg') }}" alt="">
                    <div class="chat__bubble">Hello, Is this still available?</div>
                </div>

                <div class="chat__row chat__row--me">
                    <div class="chat__bubble">Yes, Still Interested?</div>
                </div>
            </div>

            <form class="chat__compose" onsubmit="return false;">
                <x-icon name="file" :size="18" style="color:#1a73e8" />
                <input type="text" placeholder="Text message form MTN">
                <div class="tools">
                    <x-icon name="smile" :size="18" />
                    <x-icon name="image" :size="18" />
                    <span class="gif">GIF</span>
                    <x-icon name="image" :size="18" />
                </div>
            </form>
        </section>
    </div>
@endsection
