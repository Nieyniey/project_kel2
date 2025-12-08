<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'selected_items' => 'required'
        ]);

        // Array berisi ID cart_item
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
            'status' => 'pending',
        ]);

        foreach ($selected as $cartItemId) {

            // Ambil cart item berdasarkan ID
            $item = CartItem::with('product')->find($cartItemId);

            if (!$item) continue;

            $product = $item->product;
            $qty     = $item->qty; // qty dari DB — INI YANG BENAR

            // Cek stok
            if ($qty > $product->stock) {
                return back()->with('error', 'Stok tidak cukup untuk: ' . $product->name);
            }

            // Simpan di order_items
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'qty' => $qty,
                'price_per_item' => $product->price,
            ]);

            // Tambah total
            $total += $product->price * $qty;

            // Kurangi stok
            $product->stock -= $qty;
            $product->save();

            // Hapus dari cart
            $item->delete();
        }

        // Hitung total + ongkir
        $order->total_price = $total + 10000;
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
            return back()->with('error', 'Order tidak dapat dibatalkan.');
        }

        // Kembalikan stok
        foreach ($order->items as $item) {
            Product::where('product_id', $item->product_id)->increment('stock', $item->qty);
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' berhasil dibatalkan.');
    }

    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order belum dibayar.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' selesai.');
    }

    public function deleteOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Tidak diizinkan.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Order hanya bisa dihapus jika completed / cancelled.');
        }

        $order->items()->delete();
        $order->delete();

        return back()->with('success', 'Order berhasil dihapus.');
    }
}
