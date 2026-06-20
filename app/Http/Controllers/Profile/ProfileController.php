<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Ambil profile sekali, reusable di method lain jika perlu.
     */
    private function getProfile(): ?Profile
    {
        return Profile::first();
    }

    public function index()
    {
        $profile = $this->getProfile();
        return view('admin.profile.index', compact('profile'));
    }

    public function form()
    {
        $profile = $this->getProfile();
        return view('admin.profile.create', compact('profile'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'owner_name'        => 'required|string|max:255',
            'owner_description' => 'required|string|max:5000',
            'history'           => 'required|string|max:5000',
            'experience'        => 'required|string|max:5000',
            'location_name'     => 'required|string|max:255',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            // Tambah max size eksplisit & batasi ekstensi ketat
            'owner_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profile = Profile::firstOrNew([]);

        // Set user_id hanya saat pertama kali dibuat
        if (!$profile->exists) {
            $profile->user_id = Auth::id();
        }

        if ($request->hasFile('owner_photo')) {
            // Hapus foto lama jika ada
            if ($profile->owner_photo) {
                Storage::disk('public')->delete($profile->owner_photo);
            }

            // Nama unik agar tidak konflik
            $extension = $request->file('owner_photo')->getClientOriginalExtension();
            $filename  = 'owner_' . Str::uuid() . '.' . $extension;

            $profile->owner_photo = $request->file('owner_photo')
                ->storeAs('owner', $filename, 'public');
        }

        // Gunakan data dari $validated (sudah bersih)
        $profile->fill([
            'owner_name'        => $validated['owner_name'],
            'owner_description' => $validated['owner_description'],
            'history'           => $validated['history'],
            'experience'        => $validated['experience'],
            'location_name'     => $validated['location_name'],
            'latitude'          => $validated['latitude'],
            'longitude'         => $validated['longitude'],
        ]);

        $profile->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil disimpan.');
    }

    public function deletePhoto()
    {
        $profile = $this->getProfile();

        if ($profile && $profile->owner_photo) {
            Storage::disk('public')->delete($profile->owner_photo);
            $profile->owner_photo = null;
            $profile->save();
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function destroy()
    {
        $profile = $this->getProfile();

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