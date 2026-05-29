<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cart      = session('cart', []);
        $cartCount = collect($cart)->sum('quantity');
        $total     = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart', compact('cart', 'cartCount', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang (session).
     * Jika produk sudah ada, quantity-nya ditambah.
     */
    public function add(Request $request, int $id)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'notes'    => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::where('id', $id)
                          ->where('is_available', true)
                          ->firstOrFail();

        $cart    = session('cart', []);
        $qty     = (int) $request->input('quantity', 1);
        $notes   = $request->input('notes', '');
        $cartKey = (string) $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $qty;
            if (!empty($notes)) {
                $cart[$cartKey]['notes'] = $notes;
            }
        } else {
            $cart[$cartKey] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $qty,
                'notes'    => $notes,
                'image'    => $product->image,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', "\"{$product->name}\" berhasil ditambahkan ke keranjang!");
    }

    /**
     * Mengubah quantity item di keranjang.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart    = session('cart', []);
        $cartKey = (string) $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Menghapus satu item dari keranjang.
     */
    public function remove(int $id)
    {
        $cart    = session('cart', []);
        $cartKey = (string) $id;

        $removedName = $cart[$cartKey]['name'] ?? 'Item';
        unset($cart[$cartKey]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', "\"{$removedName}\" dihapus dari keranjang.");
    }

    /**
     * Mengosongkan seluruh keranjang.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
