<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show product detail
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $similarProducts = Product::where('category_id', $product->category_id)
                                    ->where('product_id', '!=', $product->product_id)
                                    ->limit(6)
                                    ->get();

        return view('buyer.detailProduct', [
            'product' => $product,
            'similarProducts' => $similarProducts
         ]);
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
