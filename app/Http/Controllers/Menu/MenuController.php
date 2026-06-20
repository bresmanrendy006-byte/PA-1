<?php

namespace App\Http\Controllers\Menu;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('menu', 'public');
            }

            Menu::create($data);

            return redirect()->route('admin.menu.index')
                ->with('success', 'Menu berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Menu store error: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan menu.')->withInput();
        }
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name'        => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $oldImage = $menu->image;

            // Upload gambar baru DULU, baru update DB, baru hapus gambar
            // lama — supaya kalau upload gagal di tengah jalan, gambar lama
            // tidak ikut terhapus sia-sia.
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('menu', 'public');
            }

            $menu->update($data);

            if ($request->hasFile('image') && $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            return redirect()->route('admin.menu.index')
                ->with('success', 'Menu berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Menu update error: ' . $e->getMessage(), [
                'menu_id' => $menu->id,
            ]);

            return back()->with('error', 'Gagal memperbarui menu.')->withInput();
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->delete();

            return back()->with('success', 'Menu berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Menu destroy error: ' . $e->getMessage(), [
                'menu_id' => $menu->id,
            ]);

            return back()->with('error', 'Gagal menghapus menu.');
        }
    }

    public function guestMenu()
    {
        $menus = Menu::latest()->get();
        return view('guest.pages.menu', compact('menus'));
    }
}