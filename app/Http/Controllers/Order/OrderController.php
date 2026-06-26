<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
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

        // Cek ketersediaan stok sebelum transaksi dimulai
        foreach ($carts as $cart) {
            $stock = Stock::where('menu_id', $cart->menu_id)->first();

            if (!$stock) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', "Menu \"{$cart->menu->name}\" belum memiliki stok. Hubungi pihak kedai.");
            }

            if ($stock->quantity < $cart->quantity) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', "Stok \"{$cart->menu->name}\" tidak mencukupi. Stok tersedia: {$stock->quantity} porsi.");
            }
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

                // Kurangi stok otomatis saat checkout
                Stock::where('menu_id', $cart->menu_id)
                    ->decrement('quantity', $cart->quantity);
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
    | Stok dikembalikan karena order pending dibatalkan untuk diedit ulang.
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

                // Kembalikan stok karena order dibatalkan untuk diedit
                Stock::where('menu_id', $item->menu_id)
                    ->increment('quantity', $item->quantity);
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
    | Stok dikembalikan karena customer membatalkan order pending.
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok untuk setiap item yang dibatalkan
            foreach ($order->items as $item) {
                Stock::where('menu_id', $item->menu_id)
                    ->increment('quantity', $item->quantity);
            }

            $order->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Cancel order failed: ' . $e->getMessage(), [
                'user_id'  => Auth::id(),
                'order_id' => $id,
            ]);
            return back()->with('error', 'Gagal membatalkan pesanan.');
        }

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