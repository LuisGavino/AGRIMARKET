<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Seller\ListingController as SellerListingController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AgriMarket – unified routes (Customer interface + Administrator interface)
|--------------------------------------------------------------------------
| Customer routes keep their original URLs and names (landing, home, products,
| wishlist, settings.*, seller.* ...). Administrator routes live under /admin
| and are named admin.* (admin.dashboard, admin.users, ...).
|
| Pages are Blade views. Authentication ("auth") and authorization ("admin")
| are separate middleware. "cache.headers" stops the browser from showing a
| protected page from its cache after logout.
*/

$signedIn = ['auth', 'cache.headers:no_store;private'];
$adminOnly = ['auth', 'admin', 'cache.headers:no_store;private'];

/*
|--------------------------------------------------------------------------
| Customer interface
|--------------------------------------------------------------------------
*/

// ---- Public pages (top navbar) -------------------------------------------
Route::view('/', 'pages.landing')->name('landing');
Route::view('/about', 'pages.about')->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{listing}', [ProductController::class, 'show'])->whereNumber('listing')->name('products.show');

// ---- Authentication (shared by customers and administrators) -------------
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::view('/signup', 'auth.signup')->name('signup');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('signup.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

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

    // A customer reads only their own orders (ownership is checked in the controller).
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->whereNumber('order')->name('orders.show');

    Route::prefix('seller')->name('seller.')->group(function () {
        Route::redirect('/', '/seller/listings');
        Route::get('/listings', [SellerListingController::class, 'index'])->name('listings');
        Route::view('/listings/new', 'seller.choose-type')->name('choose-type');
        Route::get('/listings/new/{type}', [SellerListingController::class, 'create'])
            ->whereIn('type', array_keys(Listing::TYPES))
            ->name('create');
        Route::post('/listings/new/{type}', [SellerListingController::class, 'store'])
            ->whereIn('type', array_keys(Listing::TYPES))
            ->name('store');
        Route::patch('/listings/{listing}/sold', [SellerListingController::class, 'markSold'])
            ->whereNumber('listing')
            ->name('sold');
    });
});

/*
|--------------------------------------------------------------------------
| Administrator interface  (/admin/*, route names admin.*)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () use ($adminOnly) {
    // Administrator sign-in page: the shared login view and the shared login handler.
    Route::view('/login', 'auth.login')->middleware('guest')->name('login');

    Route::middleware($adminOnly)->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users');
        Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->whereNumber('user')->name('users.edit');
        Route::put('/users/{user}', [Admin\UserController::class, 'update'])->whereNumber('user')->name('users.update');
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->whereNumber('user')->name('users.destroy');

        // Produce, Livestock and Service Providers share one controller. The type is a
        // route default, so it comes from the URL and never from submitted form data.
        foreach ([
            'produce' => ['produce', Listing::TYPE_PRODUCE],
            'livestock' => ['livestock', Listing::TYPE_LIVESTOCK],
            'service-providers' => ['service-providers', Listing::TYPE_SERVICE],
        ] as $path => [$name, $type]) {
            $c = Admin\ListingController::class;
            Route::get("/$path", [$c, 'index'])->defaults('type', $type)->name($name);
            Route::get("/$path/create", [$c, 'create'])->defaults('type', $type)->name("$name.create");
            Route::post("/$path", [$c, 'store'])->defaults('type', $type)->name("$name.store");
            Route::get("/$path/{listing}", [$c, 'show'])->whereNumber('listing')->defaults('type', $type)->name("$name.show");
            Route::get("/$path/{listing}/edit", [$c, 'edit'])->whereNumber('listing')->defaults('type', $type)->name("$name.edit");
            Route::put("/$path/{listing}", [$c, 'update'])->whereNumber('listing')->defaults('type', $type)->name("$name.update");
            Route::delete("/$path/{listing}", [$c, 'destroy'])->whereNumber('listing')->defaults('type', $type)->name("$name.destroy");
        }

        // Categories
        Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories');
        Route::get('/categories/create', [Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [Admin\CategoryController::class, 'edit'])->whereNumber('category')->name('categories.edit');
        Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->whereNumber('category')->name('categories.update');
        Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->whereNumber('category')->name('categories.destroy');

        // Orders: read and change status only (no create, edit or delete).
        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->whereNumber('order')->name('orders.show');
        Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->whereNumber('order')->name('orders.status');

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::redirect('/', '/admin/settings/account');
            Route::view('/account', 'admin.settings.account')->name('account');
            Route::view('/security', 'admin.settings.security')->name('security');
        });
    });
});
