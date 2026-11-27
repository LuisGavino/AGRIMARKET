<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AgriMarket – unified routes (Customer interface + Administrator interface)
|--------------------------------------------------------------------------
| Customer routes keep their original URLs and names (landing, home, products,
| wishlist, settings.*, seller.* ...). Administrator routes live under /admin
| and are named admin.* (admin.dashboard, admin.users, ...).
|
| Every route still just renders a Blade view (front-end only). Role checks are
| wired in but OFF by default, because the current login screen is a static
| mock-up that cannot sign anyone in. Once real authentication exists, set
| AGRIMARKET_ENFORCE_ROLES=true in .env to require login for signed-in customer
| pages and an administrator account for /admin/* (see config/agrimarket.php).
*/

$signedIn = config('agrimarket.enforce_roles') ? ['auth'] : [];
$adminOnly = config('agrimarket.enforce_roles') ? ['auth', 'admin'] : [];

/*
|--------------------------------------------------------------------------
| Customer interface
|--------------------------------------------------------------------------
*/

// ---- Public pages (top navbar) -------------------------------------------
Route::view('/', 'pages.landing')->name('landing');
Route::view('/about', 'pages.about')->name('about');
Route::view('/products', 'pages.products')->name('products');
Route::view('/login', 'auth.login')->name('login');
Route::view('/signup', 'auth.signup')->name('signup');

// ---- Signed-in pages (sidebar layout) ------------------------------------
Route::middleware($signedIn)->group(function () {
    Route::view('/home', 'app.home')->name('home');
    Route::view('/search', 'app.search')->name('search');
    Route::view('/notifications', 'app.notifications')->name('notifications');
    Route::view('/messages', 'app.messages')->name('messages');
    Route::view('/wishlist', 'app.wishlist')->name('wishlist');
    Route::view('/checkout', 'app.checkout')->name('checkout');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', '/settings/account');
        Route::view('/account', 'settings.account')->name('account');
        Route::view('/security', 'settings.security')->name('security');
    });

    Route::prefix('seller')->name('seller.')->group(function () {
        Route::redirect('/', '/seller/listings');
        Route::view('/listings', 'seller.listings')->name('listings');
        Route::view('/listings/new', 'seller.choose-type')->name('choose-type');
        Route::get('/listings/new/{type}', fn (string $type) => view('seller.create', ['type' => $type]))
            ->whereIn('type', ['produce', 'livestock', 'service'])
            ->name('create');
    });
});

/*
|--------------------------------------------------------------------------
| Administrator interface  (/admin/*, route names admin.*)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () use ($adminOnly) {
    // Administrator sign-in: the shared login view, continuing to the admin dashboard.
    Route::view('/login', 'auth.login', ['redirectRoute' => 'admin.dashboard'])->name('login');

    Route::middleware($adminOnly)->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/users', 'admin.users')->name('users');
        Route::view('/produce', 'admin.produce')->name('produce');
        Route::view('/livestock', 'admin.livestock')->name('livestock');
        Route::view('/service-providers', 'admin.service-providers')->name('service-providers');

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::redirect('/', '/admin/settings/account');
            Route::view('/account', 'admin.settings.account')->name('account');
            Route::view('/security', 'admin.settings.security')->name('security');
        });
    });
});
