<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class WTSController extends Controller
{
    public function index()
    {
        $products = Product::all();  // Read logic from `products` table
        return view('layouts.HomePageMain', compact('products'));
    }

}
