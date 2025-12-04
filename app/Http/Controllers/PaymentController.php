<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Address;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function page($order_id)
    {
        $order = Order::with('items.product')->findOrFail($order_id);

        // Ambil alamat default user
        $address = Address::where('user_id', auth()->id())
            ->where('is_default', 1)
            ->first();

        if (!$address) {
            $address = (object)[
                'address_text' => 'Alamat belum diatur',
                'city' => '',
                'postal_code' => ''
            ];
        }

        $paymentMethods = [
            [
                'name' => 'Card',
                'icons' => ['visa.png', 'mastercard.png', 'paypal.png']
            ],
            [
                'name' => 'Bank Transfer',
                'icons' => ['bca.png', 'bri.png', 'bni.png']
            ],
            [
                'name' => 'E-Money',
                'icons' => ['ovo.png', 'dana.png', 'gopay.png']
            ],
        ];

        return view('buyer.keranjang.payment',
            compact('order', 'address', 'paymentMethods'));
    }

    public function pay(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        if (!$request->method) {
            return back()->with('error', 'Please select a payment method.');
        }

        Payment::create([
            'order_id' => $order_id,
            'method'   => $request->method,
            'status'   => 'completed',
        ]);

        $order->update(['status' => 'paid']);

        return redirect()
            ->route('buyer.settings', ['tab' => 'orders'])
            ->with('success', 'Payment Successful!');
    }

}
