<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        $carts = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        $total = $carts->sum(function ($cart) {
            return $cart->quantity * $cart->menu->price;
        });

        $totalItems = $carts->sum('quantity');

        // Cek apakah ada pesanan pending
        $pendingOrder = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        return view('guest.cart.index', compact('carts', 'total', 'totalItems', 'pendingOrder'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, $menuId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'notes'    => 'nullable|string|max:500',
            ]);

            $menu  = Menu::findOrFail($menuId);
            $notes = $validated['notes'] ?? null;

            // Cari cart dengan menu_id DAN notes yang sama
            $cart = Cart::where('user_id', Auth::id())
                ->where('menu_id', $menuId)
                ->where(function ($query) use ($notes) {
                    if ($notes) {
                        $query->where('notes', $notes);
                    } else {
                        $query->whereNull('notes')->orWhere('notes', '');
                    }
                })
                ->first();

            if ($cart) {
                $cart->increment('quantity', $validated['quantity']);
            } else {
                $cart = Cart::create([
                    'user_id'  => Auth::id(),
                    'menu_id'  => $menuId,
                    'quantity' => $validated['quantity'],
                    'notes'    => $notes,
                ]);
            }

            $cart->refresh();

            $message = $cart->wasRecentlyCreated
                ? 'Item berhasil ditambahkan ke keranjang!'
                : 'Jumlah pesanan diperbarui di keranjang!';

            if ($request->ajax()) {
                return response()->json([
                    'success'    => true,
                    'message'    => $message,
                    'cart_count' => $this->getCartCountData(),
                    'cart_item'  => [
                        'id'       => $cart->id,
                        'quantity' => $cart->quantity,
                        'subtotal' => $cart->subtotal,
                    ],
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $e->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan item.',
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal menambahkan item ke keranjang.');
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:999',
            ]);

            $cart = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $cart->update(['quantity' => $validated['quantity']]);

            $response = [
                'success'           => true,
                'message'           => 'Jumlah item berhasil diperbarui',
                'subtotal'          => $cart->subtotal,
                'cart_count'        => $this->getCartCountData(),
                'formatted_subtotal' => $cart->formatted_subtotal,
            ];

            if ($request->ajax()) {
                return response()->json($response);
            }

            return redirect()->route('cart.index')->with('success', $response['message']);

        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $e->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors());

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui item.',
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal memperbarui keranjang.');
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy($id)
    {
        try {
            $cart = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $cart->delete();

            $response = [
                'success'    => true,
                'message'    => 'Item berhasil dihapus dari keranjang',
                'cart_count' => $this->getCartCountData(),
            ];

            if (request()->ajax()) {
                return response()->json($response);
            }

            return redirect()->route('cart.index')->with('success', $response['message']);

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus item.',
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus item dari keranjang.');
        }
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        try {
            $deletedCount = Cart::where('user_id', Auth::id())->delete();

            if ($deletedCount === 0) {
                return redirect()->route('cart.index')->with('info', 'Keranjang sudah kosong.');
            }

            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengosongkan keranjang.');
        }
    }

    /**
     * Get cart count for navbar
     */
    public function getCartCount()
    {
        return response()->json([
            'success' => true,
            'count'   => $this->getCartCountData(),
        ]);
    }

    /**
     * Get cart count data (private method)
     */
    private function getCartCountData()
    {
        return Cart::where('user_id', Auth::id())->sum('quantity');
    }
}