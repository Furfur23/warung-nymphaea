<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman katalog utama.
     * Mendukung filter berdasarkan slug kategori via query string: ?category=makanan-utama
     */
    public function index(Request $request)
    {
        // Ambil semua kategori yang memiliki minimal 1 produk tersedia
        $categories = Category::whereHas('availableProducts')->get();

        // Query dasar: hanya produk yang tersedia
        $query = Product::where('is_available', true)->with('category');

        // Terapkan filter kategori jika ada parameter ?category=
        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->category)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        $products = $query->latest()->get();

        // Hitung total item di keranjang untuk badge navbar
        $cartCount = collect(session('cart', []))->sum('quantity');

        return view('catalog', compact(
            'categories',
            'products',
            'activeCategory',
            'cartCount'
        ));
    }
}
