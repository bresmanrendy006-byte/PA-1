<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonials = Testimoni::with('user')->latest()->get();
        return view('admin.testimoni.index', compact('testimonials'));
    }

    public function destroy($id)
    {
        $testimonial = Testimoni::findOrFail($id);

        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}