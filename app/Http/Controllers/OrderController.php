<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->with('items.product')
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $subtotal = $cart->items->sum(fn($item) =>
            $item->product->price * $item->qty
        );

        $shipping = 10000;
        $total = $subtotal + $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => 1,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price_per_item' => $item->product->price
            ]);
        }

        CartItem::where('cart_id', $cart->cart_id)->delete();

        return redirect()->route('payment.page', $order->order_id);
    }

    public function trackList()
    {
        // Ambil semua order milik user
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('buyer.track.track-list', compact('orders'));
    }

    // public function trackDetail($id)
    // {
    //     // Ambil 1 order + itemnya
    //     $order = Order::with('items.product')
    //         ->where('user_id', auth()->id())
    //         ->findOrFail($id);

    //     return view('buyer.track.track-detail', compact('order'));
    // }

}
