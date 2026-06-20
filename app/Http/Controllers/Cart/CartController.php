<?php

namespace App\Http\Controllers\Cart;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            ->whereHas('menu')
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

            $wasCapped    = false;
            $limitReached = false;

            $cart = DB::transaction(function () use ($menuId, $notes, $validated, &$wasCapped, &$limitReached) {
                $allCarts = Cart::where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->get();

                $currentTotal = $allCarts->sum('quantity');
                $allowedToAdd = max(50 - $currentTotal, 0);

                $existing = $allCarts->first(function ($c) use ($menuId, $notes) {
                    if ((int) $c->menu_id !== (int) $menuId) {
                        return false;
                    }
                    return $notes ? $c->notes === $notes : empty($c->notes);
                });

                if ($allowedToAdd <= 0) {
                    $limitReached = true;
                    return $existing;
                }

                $quantityToAdd = min($validated['quantity'], $allowedToAdd);
                $wasCapped = $quantityToAdd < $validated['quantity'];

                if ($existing) {
                    $existing->update(['quantity' => $existing->quantity + $quantityToAdd]);
                    return $existing;
                }

                return Cart::create([
                    'user_id'  => Auth::id(),
                    'menu_id'  => $menuId,
                    'quantity' => $quantityToAdd,
                    'notes'    => $notes,
                ]);
            });

            if ($limitReached && !$cart) {
                $message = 'Keranjang sudah mencapai maksimal 50 item (gabungan semua menu).';

                if ($request->ajax()) {
                    return response()->json([
                        'success'    => false,
                        'message'    => $message,
                        'cart_count' => $this->getCartCountData(),
                    ], 422);
                }

                return redirect()->back()->with('error', $message);
            }

            $cart->refresh();

            if ($limitReached) {
                $message = 'Keranjang sudah mencapai maksimal 50 item (gabungan semua menu).';
            } elseif ($wasCapped) {
                $message = 'Jumlah di keranjang disesuaikan karena keranjang maksimal 50 item (gabungan semua menu).';
            } else {
                $message = $cart->wasRecentlyCreated
                    ? 'Item berhasil ditambahkan ke keranjang!'
                    : 'Jumlah pesanan diperbarui di keranjang!';
            }

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
            Log::error('Cart add error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'menu_id' => $menuId,
            ]);

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
                'quantity' => 'required|integer|min:1',
            ]);

            $wasCapped    = false;
            $limitReached = false;

            $cart = DB::transaction(function () use ($id, $validated, &$wasCapped, &$limitReached) {
                $cart = Cart::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                $otherTotal = Cart::where('user_id', Auth::id())
                    ->where('id', '!=', $cart->id)
                    ->lockForUpdate()
                    ->sum('quantity');

                $allowed = 50 - $otherTotal;

                if ($allowed < 1) {
                    $limitReached = true;
                    return $cart;
                }

                $newQuantity = min($validated['quantity'], $allowed);
                $wasCapped = $newQuantity < $validated['quantity'];

                $cart->update(['quantity' => $newQuantity]);

                return $cart;
            });

            if ($limitReached) {
                $message = 'Keranjang sudah mencapai maksimal 50 item.';

                if ($request->ajax()) {
                    return response()->json([
                        'success'  => false,
                        'message'  => $message,
                        'quantity' => $cart->quantity,
                    ], 422);
                }

                return redirect()->back()->with('error', $message);
            }

            $message = $wasCapped
                ? 'Jumlah di keranjang disesuaikan karena keranjang maksimal 50 item (gabungan semua menu).'
                : 'Jumlah item berhasil diperbarui';

            $response = [
                'success'            => true,
                'message'            => $message,
                'quantity'           => $cart->quantity,
                'subtotal'           => $cart->subtotal,
                'cart_count'         => $this->getCartCountData(),
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
                    'message' => 'Jumlah tidak valid',
                    'errors'  => $e->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart_id' => $id,
            ]);

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
            Log::error('Cart destroy error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart_id' => $id,
            ]);

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
            Log::error('Cart clear error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

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