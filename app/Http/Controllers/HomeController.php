<?php

namespace App\Http\Controllers;

use App\Models\HomeImage;

class HomeController extends Controller
{

    public function index()
    {
        $images = HomeImage::all();

        return view('guest.home', compact('images'));
    }

}