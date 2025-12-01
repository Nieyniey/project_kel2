<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerProductController extends Controller
{
    public function index() 
    {
        // example static data (replace with database later)
        $activeProducts = [
            [
                'id' => 1,
                'name' => 'Sepeda VOC',
                'price' => 1500000,
                'image' => '/images/bike.png'
            ],
            [
                'id' => 2,
                'name' => 'Sepeda VOC',
                'price' => 1500000,
                'image' => '/images/bike.png'
            ],
        ];

        $soldProducts = [
            [
                'id' => 10,
                'name' => 'Sepeda VOC',
                'price' => 1500000,
                'image' => '/images/bike.png'
            ],
        ];

        return view('seller.products', compact('activeProducts', 'soldProducts'));
    }


    public function create()
    {
        return view('seller.createProduct');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        // image upload sample code
        // foreach ($request->images as $image) {
        //     $path = $image->store('products', 'public');
        // }

        return redirect()->route('seller.products')
                         ->with('success', 'Product added!');
    }


    public function edit($id)
    {
        // dummy example
        $product = [
            'id' => $id,
            'name' => 'SEPEDA VOC ORI',
            'description' => 'REAL NO HOAX',
            'category' => 'Fashion',
            'weight' => 2.0,
            'length' => 2.0,
            'breadth' => 2.0,
            'width' => 2.0,
            'price' => 100000000000,
            'price_type' => 'negotiation',
        ];

        return view('seller.editProduct', compact('product'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        return redirect()->route('seller.products')
                         ->with('success', 'Product updated!');
    }
}
