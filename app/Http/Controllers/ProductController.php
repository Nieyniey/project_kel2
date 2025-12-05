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
                           ->orWhere('desc', 'LIKE', "%$keyword%")
                           ->get();

        return view('buyer.buyerHome', compact('products', 'keyword'));
    }

    public function searchAjax(Request $request)
    {
        // 1. Get the search query (q is the parameter name from your JavaScript)
        $keyword = $request->input('q');

        // 2. Perform the search query
        $products = Product::where('name', 'LIKE', "%$keyword%")
                        ->orWhere('desc', 'LIKE', "%$keyword%")
                        ->get([
                            'product_id', 
                            'name', 
                            'price', 
                            'image_path' // Ensure essential fields are selected
                        ]);

        // 3. Return the results as JSON
        return response()->json($products);
    }
}
