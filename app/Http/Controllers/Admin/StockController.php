<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $stocks = Stock::with('menu')->latest()->get();
        return view('admin.stock.index', compact('stocks'));
    }


    /*
    |--------------------------------------------------------------------------
    | 2. Create — tampilkan menu yang belum punya stock
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        // Hanya menu yang belum ada di tabel stocks yang bisa dipilih
        $menus = Menu::whereDoesntHave('stock')->orderBy('name')->get();

        if ($menus->isEmpty()) {
            return redirect()->route('admin.stock.index')
                ->with('error', 'Semua menu sudah memiliki stok.');
        }

        return view('admin.stock.create', compact('menus'));
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id'  => 'required|exists:menus,id|unique:stocks,menu_id',
            'quantity' => 'required|integer|min:0',
        ]);

        Stock::create($validated);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil ditambahkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $stock = Stock::with('menu')->findOrFail($id);
        return view('admin.stock.edit', compact('stock'));
    }


    /*
    |--------------------------------------------------------------------------
    | 5. Update — hanya quantity yang bisa diubah
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update($validated);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | 6. Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);

        try {
            $stock->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.stock.index')
                ->with('error', 'Stok tidak bisa dihapus karena masih digunakan di data lain.');
        }

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil dihapus.');
    }
}