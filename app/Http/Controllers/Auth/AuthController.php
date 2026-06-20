<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AuthController extends Controller
{

    // ======================
    // HALAMAN LOGIN
    // ======================

    public function login()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    // ======================
    // PROSES LOGIN
    // ======================

    public function processLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = $this->throttleKey($request);

        // Maksimal 5 percobaan gagal, lockout 60 detik
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('email'))
                ->with('error', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.");
        }

        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    // ======================
    // HALAMAN REGISTER
    // ======================

    public function register()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    // ======================
    // PROSES REGISTER
    // ======================

    public function processRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    // ======================
    // LOGOUT
    // ======================

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ======================
    // HELPER - REDIRECT BY ROLE
    // ======================

    private function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    // ======================
    // HELPER - THROTTLE KEY
    // ======================

    private function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}