<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function detail($id)
    {
        // Dummy product (replace with DB later)
        $product = (object)[
            'id' => $id,
            'name' => 'Sepeda BMX Remaja VELION | Rem DISCBRAKE | V BRAKE | Ban Jumbo',
            'seller' => 'TechStore Pro',
            'rating' => 4.9,
            'reviews' => 12500,
            'price' => 2000000,
            'description' => 'Sepeda pre-loved. Pedal masih bagus. Ban roda belum terlalu aus. Dudukan kursi nyaman dipakai.',
            'image' => '/images/bike.png'
        ];

        // Dummy similar products
        $similarProducts = [
            [
                'id' => 2,
                'name' => 'Product: Bicycle',
                'price' => 3500000,
                'image' => '/images/bike.png',
            ],
            [
                'id' => 3,
                'name' => 'Product: Bangles',
                'price' => 200000,
                'image' => '/images/bangles.png',
            ],
            [
                'id' => 4,
                'name' => 'Product: Couch',
                'price' => 4000000,
                'image' => '/images/couch.png',
            ],
            [
                'id' => 5,
                'name' => 'Product: Bicycle',
                'price' => 3500000,
                'image' => '/images/bike.png',
            ],
            [
                'id' => 6,
                'name' => 'Product: Bangles',
                'price' => 200000,
                'image' => '/images/bangles.png',
            ],
        ];

        return view('buyer.detailProduct', compact('product', 'similarProducts'));
    }
}
