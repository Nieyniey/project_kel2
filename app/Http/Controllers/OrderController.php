<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        // Pastikan selected_items diterima dari form
        $request->validate([
            'selected_items' => 'required'
        ]);

        $selected = json_decode($request->selected_items, true);

        if (!$selected || count($selected) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $total = 0;

        // Buat order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => 1,
            'total_price' => 0,
            'status' => 'pending'
        ]);

        foreach ($selected as $s) {

            $item = CartItem::with('product')->find($s['id']);

            if (!$item) continue;

            $product = $item->product;

            // CEK STOCK
            if ($s['qty'] > $product->stock) {
                return back()->with('error', 'Stock tidak cukup untuk: ' . $product->name);
            }

            // SIMPAN ORDER ITEM
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'qty' => $s['qty'],
                'price_per_item' => $product->price
            ]);

            // HITUNG TOTAL
            $total += $product->price * $s['qty'];

            // KURANGI STOCK
            $product->stock -= $s['qty'];
            $product->save();

            // HAPUS DARI CART
            $item->delete();
        }

        // UPDATE TOTAL ORDER
        $order->total_price = $total + 10000; // Ongkir
        $order->save();

        return redirect()->route('payment.page', $order->order_id);
    }

    public function trackList()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('buyer.track.track-list', compact('orders'));
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' cancelled.');
    }

    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order belum dibayar.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' completed.');
    }

    public function deleteOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Not allowed.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'hanya pesanan yang selesai atau dibatalkan yang dapat dihapus.');
        }

        $order->items()->delete();
        $order->delete();

        return back()->with('berhasil', 'Pesanan berhasil dihapus.');
    }
}
