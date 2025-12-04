<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WTSController extends Controller
{
    public function index()
    {
        $products = Product::all();  // Read logic from `products` table
        return view('layouts.HomePageMain', compact('products'));
    }

    public function show()
    {
        $products = Product::all(); 
        $isLoggedIn = Auth::check();

        return view('buyer.buyerHome', compact('products', 'isLoggedIn'));
    }

}
