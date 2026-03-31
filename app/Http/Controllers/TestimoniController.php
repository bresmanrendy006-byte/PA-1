<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{

    public function index()
    {
        $testimonis = Testimoni::with('user')->latest()->get();
        return view('guest.testimoni', compact('testimonis'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'isi' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fotoPath = null;

        if($request->hasFile('foto')){
            $fotoPath = $request->file('foto')->store('testimoni','public');
        }

        Testimoni::create([
            'user_id' => Auth::id(),
            'isi' => $request->isi,
            'rating' => $request->rating,
            'foto' => $fotoPath
        ]);

        return back()->with('success','Testimoni berhasil ditambahkan');
    }


    public function update(Request $request, $id)
    {

        $testimoni = Testimoni::findOrFail($id);

        if($testimoni->user_id != Auth::id()){
            abort(403);
        }

        $request->validate([
            'isi' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'isi' => $request->isi,
            'rating' => $request->rating
        ];

        if($request->hasFile('foto')){

            if($testimoni->foto){
                Storage::disk('public')->delete($testimoni->foto);
            }

            $data['foto'] = $request->file('foto')->store('testimoni','public');
        }

        $testimoni->update($data);

        return back()->with('success','Testimoni berhasil diupdate');
    }


    public function destroy($id)
    {

        $testimoni = Testimoni::findOrFail($id);

        if(Auth::user()->role == 'owner' || $testimoni->user_id == Auth::id()){

            if($testimoni->foto){
                Storage::disk('public')->delete($testimoni->foto);
            }

            $testimoni->delete();
        }

        return back()->with('success','Testimoni berhasil dihapus');
    }

}