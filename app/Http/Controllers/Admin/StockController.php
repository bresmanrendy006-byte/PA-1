<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Database\QueryException;
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
        $validated = $this->validatedData($request);

        Stock::create($validated);

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
        $stock = Stock::findOrFail($id);

        $validated = $this->validatedData($request, $stock->id);

        $stock->update($validated);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

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


    private function validatedData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'name'     => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',
                'unique:stocks,name,' . $ignoreId,
            ],
            'quantity' => 'required|integer|min:0',
            'unit'     => 'required|in:kg,liter,pcs',
        ]);
    }
}