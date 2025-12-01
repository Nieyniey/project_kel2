<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track()
    {
        // DUMMY DATA
        $orders = [
            [
                'shop' => 'TokoSepedaPro',
                'status' => 'Dikirim',  // status yang muncul di kanan atas
                'image' => 'item1.jpg',
                'name' => 'Sepeda BMX Remaja VELON | Rem DISC BRAKE',
                'desc' => 'Ukuran 16 inci – 12 Gear',
                'price' => 1200000,
                'id' => 1
            ],
            [
                'shop' => 'PedalMasterShop',
                'status' => 'Selesai',
                'image' => 'item2.jpg',
                'name' => 'Sepeda MTB XC Elite 27.5 – Alloy Frame',
                'desc' => 'Cocok untuk Off-road & Track Ringan',
                'price' => 1350000,
                'id' => 2
            ]
        ];

        return view('layouts.TrackOrder', compact('orders'));
    }
}
