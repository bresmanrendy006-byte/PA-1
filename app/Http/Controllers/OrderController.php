<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | 1. Checkout Cart → Order
    |--------------------------------------------------------------------------
    */

    public function checkout()
    {

        $userId = Auth::id();

        // Cek apakah masih ada pesanan pending
        $hasPendingOrder = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingOrder) {

            $pendingOrder = Order::where('user_id', $userId)
                ->where('status', 'pending')
                ->first();

            return redirect()
                ->route('orders.show', $pendingOrder->id)
                ->with('error', 'Pesanan sebelumnya masih menunggu konfirmasi.');
        }

        // Ambil cart user
        $carts = Cart::with('menu')
            ->where('user_id', $userId)
            ->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {

            // Hitung total harga
            $totalPrice = 0;

            foreach ($carts as $cart) {
                $totalPrice += $cart->quantity * $cart->menu->price;
            }

            // Buat order
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Simpan item order
            foreach ($carts as $cart) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $cart->menu_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->menu->price
                ]);
            }

            // Kosongkan cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal membuat pesanan.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 2. Daftar Order User
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $orders = Order::with('items.menu')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('guest.orders.index', compact('orders'));
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Detail Order
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $order = Order::with('items.menu')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('guest.orders.show', compact('order'));
    }
}