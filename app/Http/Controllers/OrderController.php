<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Place order from cart
    public function place()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if (!$cart) return back()->with('error', 'Cart is empty');

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total'  => $cart->items->sum(fn($i) => $i->product->price * $i->qty),
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->product->price,
            ]);
        }

        $cart->items()->delete(); // Clear cart after order
        return redirect()->route('orders.show', $order->id);
    }

    // Track order
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('order.show', compact('order'));
    }
}
