<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    // ======================
    // HALAMAN LOGIN
    // ======================

    public function login()
    {
        return view('auth.login');
    }

    // ======================
    // PROSES LOGIN
    // ======================

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role == 'owner' || $user->role == 'karyawan') {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
        }

        return back()->with('error', 'Email atau Password salah');
    }
    // ======================
    // HALAMAN REGISTER
    // ======================

    public function register()
    {
        return view('auth.register');
    }

    // ======================
    // PROSES REGISTER
    // ======================

    public function processRegister(Request $request)
    {
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer'
        ]);

        return redirect()->route('login');
    }

    // ======================
    // LOGOUT
    // ======================

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}