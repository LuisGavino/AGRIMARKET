@extends('layouts.app')

@section('title', 'Notifications – AgriMarket')

@section('content')
    <div class="inbox">
        <div class="inbox__top">Notifications <x-icon name="chevron-down" :size="10" /></div>

        <div class="inbox__tabs">
            <span class="is-active">All <span class="badge">1</span></span>
            <span>Alerts <span class="badge">9+</span></span>
            <span>Newsletter</span>
        </div>

        <div class="notice is-unread">
            <div class="notice__icon"><x-icon name="bell" :size="12" /></div>
            <div>
                <div class="notice__title">Confirm Login</div>
                <div class="notice__text">Checking if you actually logged in Surigao City</div>
                <div class="notice__time">Today at 9:42 AM</div>
            </div>
            <span class="notice__dot"></span>
        </div>

        <div class="notice">
            <div class="notice__icon"><x-icon name="bell" :size="12" /></div>
            <div>
                <div class="notice__title">Check Messages</div>
                <div class="notice__text">Someone messaged you! checked it out</div>
                <div class="notice__time">Today at 9:42 AM</div>
            </div>
        </div>

        <div class="notice">
            <div class="notice__icon"><x-icon name="bell" :size="12" /></div>
            <div>
                <div class="notice__title">Is this item Sold?</div>
                <div class="notice__text">Confirm is this item sold, if not click the button below to renew listing.</div>
                <div class="notice__actions">
                    <button type="button" class="btn btn--primary">Renew Listing</button>
                    <button type="button" class="btn btn--ghost">Sold Item</button>
                </div>
                <div class="notice__time">Today at 9:42 AM</div>
            </div>
        </div>

        <div class="inbox__footer">Inbox by Novu</div>
    </div>
@endsection
