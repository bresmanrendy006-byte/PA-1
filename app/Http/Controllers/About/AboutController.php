<?php

namespace App\Http\Controllers\About;
use App\Http\Controllers\Controller;
use App\Models\Profile;

class AboutController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('guest.pages.about', compact('profile'));
    }
}