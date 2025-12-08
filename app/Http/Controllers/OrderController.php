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
        // Validasi input dari JS
        $request->validate([
            'selected_items' => 'required'
        ]);

        $selected = json_decode($request->selected_items, true);

        if (!$selected || count($selected) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $total = 0;

        // BUAT ORDER BARU
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => 1, // nanti diganti address user
            'total_price' => 0,
            'status' => 'pending'
        ]);

        // LOOP ITEMS DARI FRONTEND
        foreach ($selected as $s) {

            // ->
            // s HARUSNYA ISI: [ "id" => 22, "qty" => 3 ]
            // <-

            // FIX DEPLOY BUG: pastikan id selalu integer
            $cartItemId = intval($s['id']);
            $qty = intval($s['qty']);

            // Ambil cart item (HARUS ->first() biar tidak jadi collection)
            $item = CartItem::with('product')
                ->where('cart_item_id', $cartItemId)
                ->first();

            if (!$item) continue;

            $product = $item->product;

            // CEK STOK
            if ($qty > $product->stock) {
                return back()->with('error', 'Stok tidak cukup untuk: ' . $product->name);
            }

            // SIMPAN ORDER ITEM
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'qty' => $qty,
                'price_per_item' => $product->price
            ]);

            // HITUNG TOTAL
            $total += ($product->price * $qty);

            // KURANGI STOK
            $product->stock -= $qty;
            $product->save();

            // HAPUS DARI CART
            $item->delete();
        }

        // SET TOTAL ORDER (produk + ongkir)
        $shippingCost = 10000;
        $order->total_price = $total + $shippingCost;
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

        // Kembalikan stok produk
        foreach ($order->items as $item) {
            Product::where('product_id', $item->product_id)
                ->increment('stock', $item->qty);
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
