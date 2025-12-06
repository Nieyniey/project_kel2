<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show product detail
    public function show($id)
    {
        $product = Product::with('seller')->findOrFail($id);

        $isFavorite = false;

        if (auth()->check()) {

            // ambil wishlist user atau bikin kalo belum ada
            $wishlist = \App\Models\Wishlist::firstOrCreate([
                'user_id' => auth()->id()
            ]);

            // cek apakah produk sudah ada di wishlist_items
            $isFavorite = \App\Models\WishlistItem::where('wishlist_id', $wishlist->id)
                            ->where('product_id', $product->product_id)
                            ->exists();
        }

        // Similar products
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->take(6)
            ->get();

        return view('buyer.detailProduct', compact(
            'product',
            'similarProducts',
            'isFavorite'
        ));
    }

    // Search products
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $products = Product::where('name', 'LIKE', "%$keyword%")
                           ->orWhere('description', 'LIKE', "%$keyword%")
                           ->paginate(12);

        return view('buyer.buyerHome', compact('products', 'keyword'));
    }
}
