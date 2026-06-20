<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $pendingOrder = Order::where('user_id', $userId)
            ->where('status', Order::STATUS_PENDING)
            ->first();

        if ($pendingOrder) {
            return redirect()
                ->route('orders.show', $pendingOrder->id)
                ->with('error', 'Pesanan sebelumnya masih menunggu konfirmasi.');
        }

        $carts = Cart::with('menu')
            ->where('user_id', $userId)
            ->whereHas('menu')
            ->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        if ($carts->sum('quantity') > 50) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Total item di keranjang melebihi batas maksimal 50. Mohon sesuaikan jumlahnya sebelum checkout.');
        }

        DB::beginTransaction();

        try {
            $totalPrice = $carts->sum(fn($cart) => $cart->quantity * $cart->menu->price);

            $order = Order::create([
                'user_id'     => $userId,
                'total_price' => $totalPrice,
                'status'      => Order::STATUS_PENDING,
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $cart->menu_id,
                    'quantity' => $cart->quantity,
                    'price'    => $cart->menu->price,
                ]);
            }

            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage(), ['user_id' => $userId]);
            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 2. Daftar Order Customer
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


    /*
    |--------------------------------------------------------------------------
    | 4. Edit Pesanan → Kembalikan ke Cart
    |--------------------------------------------------------------------------
    */

    public function editToCart($id)
    {
        $userId = Auth::id();

        $order = Order::with('items.menu')
            ->where('user_id', $userId)
            ->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Hanya pesanan pending yang bisa diedit.');
        }

        DB::beginTransaction();

        try {
            Cart::where('user_id', $userId)->delete();

            foreach ($order->items as $item) {
                Cart::create([
                    'user_id'  => $userId,
                    'menu_id'  => $item->menu_id,
                    'quantity' => $item->quantity,
                    'notes'    => null,
                ]);
            }

            $order->delete();

            DB::commit();

            return redirect()
                ->route('cart.index')
                ->with('success', 'Pesanan berhasil dikembalikan ke keranjang. Silakan ubah pesanan Anda.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('editToCart failed: ' . $e->getMessage(), [
                'user_id'  => $userId,
                'order_id' => $id,
            ]);
            return back()->with('error', 'Gagal mengembalikan pesanan ke keranjang.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 5. Cancel Order
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | 6. Delete Order
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== Order::STATUS_REJECTED) {
            return back()->with('error', 'Pesanan hanya bisa dihapus jika berstatus ditolak.');
        }

        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

}