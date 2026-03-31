<?php

namespace App\Http\Controllers;

use App\Models\HomeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeImageController extends Controller
{

    public function index()
    {
        $images = HomeImage::all();
        return view('admin.kelolahome', compact('images'));
    }

    public function store(Request $request)
    {
        $path = $request->file('image')->store('home','public');

        HomeImage::create([
            'image' => $path
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $image = HomeImage::findOrFail($id);

        if($request->hasFile('image')){

            // hapus gambar lama
            Storage::disk('public')->delete($image->image);

            // upload gambar baru
            $path = $request->file('image')->store('home','public');

            $image->update([
                'image' => $path
            ]);
        }

        return back();
    }

    public function destroy($id)
    {
        $image = HomeImage::findOrFail($id);

        Storage::disk('public')->delete($image->image);

        $image->delete();

        return back();
    }
}