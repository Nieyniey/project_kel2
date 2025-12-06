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
        return view('buyer.detailProduct', compact('product'));
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
