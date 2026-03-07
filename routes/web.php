<?php

use Illuminate\Support\Facades\Route;

// Route untuk halaman home
Route::get('/', function () {
    return view('guest.home');
})->name('home');

// Route untuk halaman lainnya (contoh)
Route::get('/about', function () {
    return view('guest.about');
})->name('about');

Route::get('/menu', function () {
    return view('guest.menu');
})->name('menu');

Route::get('/testimoni', function () {
    return view('guest.testimoni');
})->name('testimoni');

Route::get('/contact', function () {
    return view('guest.contact');
})->name('contact');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');