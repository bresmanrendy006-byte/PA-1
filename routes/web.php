<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\Admin\TestimoniController as AdminTestimoniController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Guest Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/about',[AboutController::class,'index'])->name('about');
Route::get('/menu', [MenuController::class,'guestMenu'])->name('guest.menu');
Route::get('/testimoni',[TestimoniController::class,'index'])->name('testimoni');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Customer Area (Login Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::post('/cart/add/{menuId}', [CartController::class, 'add'])->name('cart.add');

    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');

    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/api/cart/count', [CartController::class, 'getCartCount'])->name('api.cart.count');


    /*
    |--------------------------------------------------------------------------
    | Checkout & Orders
    |--------------------------------------------------------------------------
    */

    Route::post('/checkout', [OrderController::class,'checkout'])->name('checkout');

    Route::get('/orders', [OrderController::class,'index'])->name('orders.index');

    Route::get('/orders/{id}', [OrderController::class,'show'])->name('orders.show');


    /*
    |--------------------------------------------------------------------------
    | Testimoni Customer
    |--------------------------------------------------------------------------
    */

    Route::post('/testimoni',[TestimoniController::class,'store'])->name('testimoni.store');

    Route::put('/testimoni/{id}',[TestimoniController::class,'update'])->name('testimoni.update');

    Route::delete('/testimoni/{id}',[TestimoniController::class,'destroy'])->name('testimoni.destroy');

});


/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
->prefix('admin')
->name('admin.')
->group(function () {

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Kelola Home
    |--------------------------------------------------------------------------
    */

    Route::get('/kelola-home',[HomeImageController::class,'index'])->name('kelolahome');

    Route::post('/kelola-home',[HomeImageController::class,'store'])->name('home.store');

    Route::put('/kelola-home/{id}',[HomeImageController::class,'update'])->name('home.update');

    Route::delete('/kelola-home/{id}',[HomeImageController::class,'destroy'])->name('home.delete');


    /*
    |--------------------------------------------------------------------------
    | Kelola Profile
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->group(function(){

        Route::get('/', [ProfileController::class,'index'])->name('profile.index');

        Route::get('/form', [ProfileController::class,'form'])->name('profile.form');

        Route::post('/save', [ProfileController::class,'save'])->name('profile.save');

        Route::delete('/clear',[ProfileController::class,'clear'])->name('profile.clear');

        Route::post('/delete-photo', [ProfileController::class,'deletePhoto'])->name('profile.deletePhoto');

        Route::delete('/destroy', [ProfileController::class,'destroy'])->name('profile.destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | Kelola Menu
    |--------------------------------------------------------------------------
    */

    Route::get('/menu',[MenuController::class,'index'])->name('menu.index');

    Route::get('/menu/create',[MenuController::class,'create'])->name('menu.create');

    Route::post('/menu/store',[MenuController::class,'store'])->name('menu.store');

    Route::get('/menu/edit/{menu}',[MenuController::class,'edit'])->name('menu.edit');

    Route::put('/menu/update/{menu}',[MenuController::class,'update'])->name('menu.update');

    Route::delete('/menu/delete/{menu}',[MenuController::class,'destroy'])->name('menu.delete');


    /*
    |--------------------------------------------------------------------------
    | Kelola Testimoni
    |--------------------------------------------------------------------------
    */

    Route::get('/kelolatestimoni',[AdminTestimoniController::class,'index'])->name('testimoni.index');

    Route::delete('/kelolatestimoni/{id}',[AdminTestimoniController::class,'destroy'])->name('testimoni.destroy');

});