<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeImage;

class HomeController extends Controller
{
    public function index()
    {
        $images = HomeImage::orderBy('id')->take(10)->get();

        return view('guest.pages.home', compact('images'));
    }
}