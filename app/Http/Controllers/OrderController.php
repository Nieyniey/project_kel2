<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
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
        $address = Address::where('user_id', Auth::id())
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

    // CANCEL ORDER
    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'Order cannot be cancelled.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' has been cancelled.');
    }

    // COMPLETE ORDER
    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order has not been paid yet.');
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Order is already completed or cancelled.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' has been marked as completed.');
    }

    // DELETE ORDER
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
