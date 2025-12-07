<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WTSController extends Controller
{
    public function index()
    {
        $products = Product::all();  
        return view('layouts.HomePageMain', compact('products'));
    }

    public function show(Request $request)
    {
        $categories = Category::all();

        $selectedCategorySlug = $request->query('category'); 

        $productQuery = Product::query();

        if ($selectedCategorySlug) {
            $selectedCategory = Category::where('slug', $selectedCategorySlug)->first();

            if ($selectedCategory) {
                $productQuery->where('category_id', $selectedCategory->category_id);
            }
        }
        
        $products = $productQuery
            ->where(function ($query) {
                $query->where('is_sale', true);
            })
            ->inRandomOrder()->paginate(12);

        $saleQuery = Product::where('is_sale', 1);

        if (isset($selectedCategory)) {
            $saleQuery->where('category_id', $selectedCategory->category_id);
        }
        $saleProducts = $saleQuery
                       ->inRandomOrder()        
                       ->get();

        return view('buyer.buyerHome', compact(
            'products', 
            'categories', 
            'selectedCategorySlug',
            'saleProducts' 
        ));
    }
}
