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
        // Ambil item yang dipilih dari form
        $selected = json_decode($request->selected_items, true);

        if (!$selected || count($selected) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        // Ambil cart user
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        // Ambil cart item berdasarkan ID yang dipilih user
        $items = CartItem::with('product')
            ->where('cart_id', $cart->cart_id)
            ->whereIn('cart_item_id', collect($selected)->pluck('id'))
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Item tidak valid.');
        }

        // Hitung subtotal
        $subtotal = 0;
        foreach ($items as $i) {
            $subtotal += $i->product->price * $i->qty;
        }

        $shipping = 10000;
        $total = $subtotal + $shipping;

        // Ambil alamat default user
        $address = \App\Models\Address::where('user_id', Auth::id())
            ->where('is_default', 1)
            ->first();

        if (!$address) {
            return back()->with('error', 'Alamat default tidak ditemukan.');
        }

        // Buat order baru
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $address->id,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        // Masukkan item ke tabel order_items
        foreach ($items as $i) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $i->product_id,
                'qty' => $i->qty,
                'price_per_item' => $i->product->price,
            ]);
        }

        // Hapus hanya item yang dipilih dari cart
        CartItem::whereIn('cart_item_id', collect($selected)->pluck('id'))->delete();

        // Redirect ke payment page
        return redirect()->route('payment.page', ['order_id' => $order->order_id]);
    }

    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('buyer.order.show', compact('order'));
    }
}
