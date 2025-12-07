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

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'Order cannot be cancelled.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' has been cancelled.');
    }

    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order have not been paid yet.');
        }

        if ($order->user_id !== Auth::id() || in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Order is already completed or cancelled.');
        }
        
        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' has been marked as completed. Thank you!');
    }

    public function deleteOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'You do not have permission to delete this order.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Only completed or cancelled orders can be deleted.');
        }

        $order->items()->delete();
        
        $order->delete();

        return back()->with('success', 'Order history #' . $order->order_id . ' has been deleted.');
    }

}
