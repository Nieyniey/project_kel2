<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = [
            [
                'image' => 'item1.jpg',
                'name' => 'Sepeda BMX 16 Inch – Black Edition',
                'desc' => 'Kondisi: 95% mulus',
                'price' => 2000000,
                'qty' => 1
            ],
            [
                'image' => 'item2.jpg',
                'name' => 'Sepeda Fixie 700C – Matte Grey',
                'desc' => 'Ringan dan cepat',
                'price' => 1500000,
                'qty' => 1
            ],
        ];

        $summary = [
            'subtotal' => 3500000,
            'shipping' => 10000,
            'total' => 3510000
        ];

        return view('layouts.cart', compact('items', 'summary'));
    }
}
