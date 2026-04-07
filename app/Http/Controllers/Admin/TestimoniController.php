<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::with('user')->latest()->get();
        return view('admin.kelolatestimoni', compact('testimonis'));
    }

    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);

        // Hapus foto dari storage jika ada
        if ($testimoni->foto) {
            Storage::disk('public')->delete($testimoni->foto);
        }

        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}