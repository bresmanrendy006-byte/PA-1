<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('admin.profile.kelolaprofile', compact('profile'));
    }

    public function form()
    {
        $profile = Profile::first();
        return view('admin.profile.tambahprofile', compact('profile'));
    }

    public function save(Request $request)
    {
        // 1. Validasi DULU sebelum proses apapun
        $request->validate([
            'owner_name'        => 'required|string|max:255',
            'owner_description' => 'required|string',
            'history'           => 'required|string',
            'experience'        => 'required|string',
            'location_name'     => 'required|string|max:255',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'owner_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profile = Profile::first();

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = Auth::id();
        }

        // 2. Proses foto setelah validasi lolos
        if ($request->hasFile('owner_photo')) {
            if ($profile->owner_photo) {
                Storage::disk('public')->delete($profile->owner_photo);
            }
            $profile->owner_photo = $request->file('owner_photo')
                ->store('owner', 'public');
        }

        // 3. Assign semua field sebelum save
        $profile->owner_name        = $request->owner_name;
        $profile->owner_description = $request->owner_description;
        $profile->history           = $request->history;
        $profile->experience        = $request->experience;
        $profile->location_name     = $request->location_name;
        $profile->latitude          = $request->latitude;
        $profile->longitude         = $request->longitude;

        $profile->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil disimpan.');
    }

    public function deletePhoto(Request $request)
    {
        $profile = Profile::first();

        if ($profile && $profile->owner_photo) {
            Storage::disk('public')->delete($profile->owner_photo);
            $profile->owner_photo = null;
            $profile->save();
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function destroy()
    {
        $profile = Profile::first();

        if ($profile) {
            if ($profile->owner_photo) {
                Storage::disk('public')->delete($profile->owner_photo);
            }
            $profile->delete();
        }

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil dihapus.');
    }
}