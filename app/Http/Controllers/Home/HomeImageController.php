<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeImageController extends Controller
{
    public function index()
    {
        $images = HomeImage::orderBy('id')->get();
        return view('admin.home.index', compact('images'));
    }

    public function store(Request $request)
    {
        // Tidak perlu try-catch ValidationException —
        // Laravel redirect back with errors otomatis.
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
        ]);

        try {
            $path = $this->uploadImage($request);

            HomeImage::create(['image' => $path]);

            return back()->with('success', 'Gambar berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('HomeImage store error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengunggah gambar. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
        ]);

        if (!$request->hasFile('image')) {
            return back()->with('info', 'Tidak ada gambar baru yang diunggah.');
        }

        $image = HomeImage::findOrFail($id);

        $newPath = null;

        try {
            // Upload dulu sebelum sentuh DB
            $newPath = $this->uploadImage($request);
            $oldPath = $image->image;

            // Transaksi DB — kalau gagal, masuk catch & hapus file baru
            DB::transaction(function () use ($image, $newPath) {
                $image->update(['image' => $newPath]);
            });

            // Hapus lama hanya setelah DB sukses
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return back()->with('success', 'Gambar berhasil diperbarui.');

        } catch (\Exception $e) {
            // Rollback: hapus file baru yang terlanjur terupload
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('HomeImage update error', [
                'home_image_id' => $id,
                'message'       => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui gambar. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        $image = HomeImage::findOrFail($id);

        try {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            $image->delete();

            return back()->with('success', 'Gambar berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('HomeImage destroy error', [
                'home_image_id' => $id,
                'message'       => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus gambar. Silakan coba lagi.');
        }
    }

    // -------------------------------------------------------
    // HELPER
    // -------------------------------------------------------

    private function uploadImage(Request $request): string
    {
        $file      = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename  = 'home_' . Str::uuid() . '.' . $extension;

        return $file->storeAs('home', $filename, 'public');
    }
}