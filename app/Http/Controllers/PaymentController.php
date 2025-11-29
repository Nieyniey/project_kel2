<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // PAGE PAYMENT
    public function payment(Request $request)
    {
        // Ambil alamat dari form (GET)
        $address = $request->address 
            ?? "13th Street, 47 W 13th St, New York, NY 10011, USA";

        // Dummy order
        $order = [
            'name' => "Sepeda BMX Remaja",
            'price' => 2000000,
            'qty' => 1,
            'shipping' => 2000,
            'total' => 2002000
        ];

        // Pilihan metode pembayaran
        $paymentMethods = [
            ['name' => 'Card', 'icons' => ['visa.png','mastercard.png']],
            ['name' => 'Transfer Bank', 'icons' => ['bca.png','bri.png']],
            ['name' => 'Google Pay', 'icons' => ['google.png']],
            ['name' => 'Apple Pay', 'icons' => ['apple.png']],
            ['name' => 'E-Money', 'icons' => ['ovo.png','dana.png']],
            ['name' => 'PayLater', 'icons' => ['kredivo.png']],
        ];

        return view('layouts.payment', compact('address','order','paymentMethods'));
    }


    // CHANGE ADDRESS PAGE
    public function changeAddress()
    {
        return view('layouts.ChangeAddress');
    }


    // SAVE ADDRESS
    public function saveAddress(Request $request)
    {
        // Ambil input lalu redirect ke /payment
        return redirect()->route('payment', [
            'address' => $request->address,
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
        ]);
    }


    // PAYMENT SUCCESS PAGE
    public function paymentSuccess()
    {
        return view('layouts.payment-success');
    }
}
