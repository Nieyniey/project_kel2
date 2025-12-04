<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay($order_id)
    {
        $order = Order::findOrFail($order_id);

        $payment = Payment::create([
            'order_id' => $order_id,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $order->update(['status' => 'paid']);

        return redirect()->route('orders.show', $order_id)
                         ->with('success', 'Payment Successful');
    }
}
