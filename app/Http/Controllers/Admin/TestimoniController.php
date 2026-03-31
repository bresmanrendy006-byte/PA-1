<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;

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
        $testimoni->delete();

        return back()->with('success','Testimoni berhasil dihapus');
    }
}