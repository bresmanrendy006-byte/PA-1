<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Halaman profile customer
     */
    public function index()
    {
        return view('guest.pages.profile', ['user' => Auth::user()]);
    }

    /**
     * Halaman profile admin/owner/karyawan
     */
    public function adminIndex()
    {
        return view('admin.account.index', ['user' => Auth::user()]);
    }

    /**
     * Ganti password — dipakai untuk semua role (customer & admin).
     * Perbedaan redirect/view ditangani di level route & middleware.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            // min:8 lebih aman, confirmed butuh field password_confirmation di form
            'password'         => 'required|string|min:8|max:100|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}