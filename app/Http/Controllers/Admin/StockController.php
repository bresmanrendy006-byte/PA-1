<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::latest()->get();
        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('admin.stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit'     => 'required|string|max:50',
        ]);

        Stock::create($request->only(['name', 'quantity', 'unit']));

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit'     => 'required|string|max:50',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->only(['name', 'quantity', 'unit']));

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil dihapus.');
    }
}