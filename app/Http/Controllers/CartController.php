<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function view()
    {
        $cartItems = Auth::user()->cartItems()->with('item.category')->get();
        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'item_id' => 'required|exists:items,id',
                'quantity' => 'required|integer|min:1|max:999',
            ]);

            $item = Item::findOrFail($request->item_id);

            if ($item->stock < $request->quantity) {
                return back()->with('error', 'Stok tidak cukup.');
            }

            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'item_id' => $request->item_id,
                ],
                ['quantity' => $request->quantity]
            );

            Log::info('Item added to cart', [
                'user_id' => Auth::id(),
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
            ]);

            return redirect()->route('cart.view')->with('success', 'Barang ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan ke keranjang.');
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        try {
            if ($cartItem->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $request->validate([
                'quantity' => 'required|integer|min:1|max:999',
            ]);

            if ($cartItem->item->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak cukup'], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'subtotal' => $cartItem->subtotal,
            ]);
        } catch (\Exception $e) {
            Log::error('Update cart error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }

    public function remove(CartItem $cartItem)
    {
        try {
            if ($cartItem->user_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized');
            }

            $cartItem->delete();

            return back()->with('success', 'Barang dihapus dari keranjang.');
        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus barang.');
        }
    }

    public function clear()
    {
        try {
            Auth::user()->cartItems()->delete();
            return back()->with('success', 'Keranjang telah dikosongkan.');
        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengosongkan keranjang.');
        }
    }
}
