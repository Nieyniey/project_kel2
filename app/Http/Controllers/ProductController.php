<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show product detail
    public function show($id)
    {
        $product = Product::with(['seller', 'category'])
                        ->where('product_id', $id)
                        ->firstOrFail();

        $isFavorite = false;

        if (auth()->check()) {
            $wishlist = \App\Models\Wishlist::firstOrCreate([
                'user_id' => auth()->id()
            ]);

            $isFavorite = \App\Models\WishlistItem::where('wishlist_id', $wishlist->id)
                            ->where('product_id', $product->product_id)
                            ->exists();
        }

        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->take(8)
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
        $selectedCategorySlug = null;
        $categories = Category::all();

        $productQuery = Product::query();

        if ($keyword) {
            $productQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }
        
        $products = $productQuery->paginate(12);

        return view('buyer.buyerHome', compact('products', 'keyword', 'selectedCategorySlug', 'categories'));
    }
}
