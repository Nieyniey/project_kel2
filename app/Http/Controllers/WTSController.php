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
        $products = Product::all();  // Read logic from `products` table
        return view('layouts.HomePageMain', compact('products'));
    }

    // public function show()
    // {
    //     $products = Product::inRandomOrder()->paginate(12); 
    //     $isLoggedIn = Auth::check();

    //     return view('buyer.buyerHome', compact('products', 'isLoggedIn'));
    // }

    public function show(Request $request)
    {
        // 1. Fetch all categories for the navigation
        $categories = Category::all();

        // 2. Get the category filter parameter (ID or Slug)
        // We'll use the 'category' slug from the query string (?category=topi)
        $selectedCategorySlug = $request->query('category'); 

        // 3. Start building the Product query
        $productQuery = Product::query();

        // 4. Apply the category filter if a category is selected
        if ($selectedCategorySlug) {
            $selectedCategory = Category::where('slug', $selectedCategorySlug)->first();

            if ($selectedCategory) {
                // Filter products based on the category_id
                $productQuery->where('category_id', $selectedCategory->category_id);
            }
            // Note: If the slug is invalid, no filter is applied, or the query returns 0 results.
        }
        
        // 5. Apply the rest of your conditions (Black Friday Sale / Recommended)
        // Assuming you have columns like 'is_black_friday_sale' and 'is_recommended'
        $products = $productQuery
            ->where(function ($query) {
                $query->where('is_sale', true);
            })
            ->inRandomOrder()->paginate(12);

        $saleProducts = Product::where('is_sale', 1)
                       ->inRandomOrder() // Optional: shuffle sale products
                       ->limit(6)       // Fetch a reasonable number for the horizontal scroll
                       ->get();

        return view('buyer.buyerHome', compact(
            'products', // Your main recommended/filtered products (paginated)
            'categories', 
            'selectedCategorySlug',
            'saleProducts' // The new collection for the sale banner
        ));
    }
}
