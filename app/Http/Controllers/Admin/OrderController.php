<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{

/*
|--------------------------------------------------------------------------
| 1. Kelola Pesanan (Pending)
|--------------------------------------------------------------------------
*/

public function index()
{

$orders = Order::with('items.menu','user')
->where('status','pending')
->latest()
->get();

return view('admin.orders.kelolapemesanan',compact('orders'));

}


/*
|--------------------------------------------------------------------------
| 2. Konfirmasi Pesanan
|--------------------------------------------------------------------------
*/

public function confirm($id)
{

$order = Order::findOrFail($id);

$order->update([
'status' => 'confirmed'
]);

return redirect()
->route('admin.orders.index')
->with('success','Pesanan berhasil dikonfirmasi');

}


/*
|--------------------------------------------------------------------------
| 3. Tolak Pesanan
|--------------------------------------------------------------------------
*/

public function reject($id)
{

$order = Order::findOrFail($id);

$order->update([
'status' => 'rejected'
]);

return redirect()
->route('admin.orders.index')
->with('success','Pesanan ditolak');

}


/*
|--------------------------------------------------------------------------
| 4. History Pesanan
|--------------------------------------------------------------------------
*/

public function history()
{

$orders = Order::with('items.menu','user')
->whereIn('status', ['confirmed', 'rejected'])
->latest()
->get();

return view('admin.orders.history',compact('orders'));

}

}