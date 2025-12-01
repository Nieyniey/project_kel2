<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function home()
    {
        // Example dummy products (replace with DB later)
        $products = [
            (object)[
                'id' => 1,
                'name' => 'Black Knit Sweater',
                'price' => 120000,
                'image' => '/images/sample1.png'
            ],
            (object)[
                'id' => 2,
                'name' => 'Brown Vintage Jacket',
                'price' => 250000,
                'image' => '/images/sample2.png'
            ],
            (object)[
                'id' => 3,
                'name' => 'White Canvas Bag',
                'price' => 90000,
                'image' => '/images/sample3.png'
            ],
        ];

        return view('buyer.buyerHome', compact('products'));
    }


    public function settings()
    {
        return view('buyer.buyerSettings');
    }


    public function favorites()
    {
        // Example dummy favorites
        $favorites = [
            [
                'id' => 10,
                'name' => 'Vintage Boots',
                'price' => 150000,
                'image' => '/images/boots.png'
            ]
        ];

        return view('buyer.buyerFavorites', compact('favorites'));
    }


    public function chat()
    {
        // Dummy chat list
        $chats = [
            [
                'id' => 1,
                'name' => 'Seller A',
                'last_message' => 'Product masih ada kak?',
                'time' => '14:20',
                'image' => '/images/profile1.png'
            ],
            [
                'id' => 2,
                'name' => 'Seller B',
                'last_message' => 'Boleh nego ya kak',
                'time' => 'Yesterday',
                'image' => '/images/profile2.png'
            ],
        ];

        return view('buyer.buyerChat', compact('chats'));
    }


    public function cart()
    {
        // Dummy cart
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Black Knit Sweater',
                'price' => 120000,
                'qty' => 1,
                'image' => '/images/sample1.png'
            ],
            [
                'id' => 2,
                'name' => 'Vintage Jacket',
                'price' => 250000,
                'qty' => 1,
                'image' => '/images/sample2.png'
            ],
        ];

        return view('buyer.buyerKeranjang', compact('cartItems'));
    }
}
