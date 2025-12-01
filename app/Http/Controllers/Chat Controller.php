<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function sellerChat()
    {
        $contacts = [
            ['name' => 'TechStore Pro', 'msg' => 'selamat siang, produk ini ready stock'],
            ['name' => 'Syariah', 'msg' => 'Halo kak, apakah masih tertarik?'],
            ['name' => 'TokoCikini452', 'msg' => 'Maaf ya kak, produknya sudah habis'],
            ['name' => 'LilyFlowers', 'msg' => 'Halo bu, vasnya masih ada ya!'],
        ];

        return view('seller.SellerChat', compact('contacts'));
    }

    public function buyerChat()
    {
        $sellers = [
            ['name' => 'Bags Vintage', 'msg' => 'Ready kak! mau warna apa?'],
            ['name' => 'TechStore Pro', 'msg' => 'Barang sudah dikirim ya kak'],
            ['name' => 'BukuAja', 'msg' => 'Mau buku apa kak?'],
        ];

        return view('buyer.buyerChat', compact('sellers'));
    }
}
