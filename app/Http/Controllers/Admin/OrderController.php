<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{

// PESANAN MASUK
public function index()
{

$orders = Order::with('items.menu','user')
->where('status','pending')
->latest()
->get();

return view('admin.orders.index',compact('orders'));

}


// KONFIRMASI PESANAN
public function confirm($id)
{

$order = Order::findOrFail($id);

$order->update([
'status' => 'confirmed'
]);

return back()->with('success','Pesanan dikonfirmasi');

}


// TOLAK PESANAN
public function reject($id)
{

$order = Order::findOrFail($id);

$order->update([
'status' => 'rejected'
]);

return back()->with('success','Pesanan ditolak');

}


// HISTORY PESANAN
public function history()
{

$orders = Order::with('items.menu','user')
->where('status','confirmed')
->latest()
->get();

return view('admin.orders.history',compact('orders'));

}

}