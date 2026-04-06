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
return view('admin.manajemenstok', compact('stocks'));
}


public function store(Request $request)
{

$request->validate([
'name' => 'required',
'quantity' => 'required|integer',
'unit' => 'required'
]);

Stock::create($request->all());

return back()->with('success','Stok berhasil ditambahkan');

}


public function update(Request $request, $id)
{

$stock = Stock::findOrFail($id);

$stock->update([
'name' => $request->name,
'quantity' => $request->quantity,
'unit' => $request->unit
]);

return back()->with('success','Stok berhasil diperbarui');

}


public function destroy($id)
{

$stock = Stock::findOrFail($id);

$stock->delete();

return back()->with('success','Stok berhasil dihapus');

}

}