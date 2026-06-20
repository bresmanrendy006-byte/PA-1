<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\About\AboutController;

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\HomeImageController;

use App\Http\Controllers\Menu\MenuController;

use App\Http\Controllers\Cart\CartController;

use App\Http\Controllers\Order\OrderController;

use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\UserProfileController;

use App\Http\Controllers\Testimoni\TestimoniController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TestimoniController as AdminTestimoniController;


/*
|--------------------------------------------------------------------------
| Guest Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/menu', [MenuController::class, 'guestMenu'])->name('guest.menu');
Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'processLogin')->name('login.process');

    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'processRegister')->name('register.process');

    Route::post('/logout', 'logout')->name('logout');
});


/*
|--------------------------------------------------------------------------
| Customer Area
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Customer Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.changePassword');


    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{menuId}', [CartController::class, 'add'])->name('add');
        Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    });


    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::delete('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/edit-to-cart', [OrderController::class, 'editToCart'])->name('editToCart');
    });


    /*
    |--------------------------------------------------------------------------
    | Customer Testimoni
    |--------------------------------------------------------------------------
    */

    Route::prefix('testimoni')->name('testimoni.')->group(function () {
        Route::post('/', [TestimoniController::class, 'store'])->name('store');
        Route::put('/{id}', [TestimoniController::class, 'update'])->name('update');
        Route::delete('/{id}', [TestimoniController::class, 'destroy'])->name('destroy');
    });

});


/*
|--------------------------------------------------------------------------
| Admin Area (owner & karyawan)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Admin User Profile & Ganti Password
        |--------------------------------------------------------------------------
        */

        Route::get('/user-profile', [UserProfileController::class, 'adminIndex'])
            ->name('userProfile');

        Route::post('/user-profile/change-password', [UserProfileController::class, 'adminChangePassword'])
            ->name('userProfile.changePassword');


        /*
        |--------------------------------------------------------------------------
        | Home Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('home')->name('home.')->group(function () {
            Route::get('/', [HomeImageController::class, 'index'])->name('index');

            Route::middleware('owner')->group(function () {
                Route::post('/', [HomeImageController::class, 'store'])->name('store');
                Route::put('/{id}', [HomeImageController::class, 'update'])->name('update');
                Route::delete('/{id}', [HomeImageController::class, 'destroy'])->name('delete');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');

            Route::middleware('owner')->group(function () {
                Route::get('/form', [ProfileController::class, 'form'])->name('form');  // ← pindah ke sini
                Route::post('/save', [ProfileController::class, 'save'])->name('save');
                Route::delete('/clear', [ProfileController::class, 'clear'])->name('clear');
                Route::post('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('deletePhoto');
                Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
            });
        });


        /*
        |--------------------------------------------------------------------------
        | Menu Management
        | index dibuka untuk semua admin-area (owner & karyawan), sama seperti
        | Stock Management di bawah; create/store/edit/update/delete dibatasi
        | ke owner lewat middleware 'owner' (bukan lagi inline check di controller).
        |--------------------------------------------------------------------------
        */

        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');

            Route::middleware('owner')->group(function () {
                Route::get('/create', [MenuController::class, 'create'])->name('create');
                Route::post('/store', [MenuController::class, 'store'])->name('store');
                Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('edit');
                Route::put('/update/{menu}', [MenuController::class, 'update'])->name('update');
                Route::delete('/delete/{menu}', [MenuController::class, 'destroy'])->name('delete');
            });
        });


        /*
        |--------------------------------------------------------------------------
        | Testimoni Management
        |--------------------------------------------------------------------------
        */

        Route::get('/testimoni', [AdminTestimoniController::class, 'index'])
            ->name('testimoni.index');

        Route::delete('/testimoni/{id}', [AdminTestimoniController::class, 'destroy'])
            ->name('testimoni.destroy');


        /*
        |--------------------------------------------------------------------------
        | Orders Management
        | PENTING: /history harus SEBELUM /{id} agar tidak tertangkap sebagai id
        |--------------------------------------------------------------------------
        */

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/history', [AdminOrderController::class, 'history'])->name('history');
            Route::post('/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('confirm');
            Route::post('/{id}/reject', [AdminOrderController::class, 'reject'])->name('reject');
        });


        /*
        |--------------------------------------------------------------------------
        | Stock Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');

            Route::middleware('owner')->group(function () {
                Route::get('/create', [StockController::class, 'create'])->name('create');
                Route::get('/edit/{id}', [StockController::class, 'edit'])->name('edit');
                Route::post('/', [StockController::class, 'store'])->name('store');
                Route::put('/{id}', [StockController::class, 'update'])->name('update');
                Route::delete('/{id}', [StockController::class, 'destroy'])->name('delete');
            });
        });

    });