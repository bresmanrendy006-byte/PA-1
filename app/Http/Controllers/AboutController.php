<?php

namespace App\Http\Controllers;

use App\Models\Profile;

class AboutController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('guest.about', compact('profile'));
    }
}