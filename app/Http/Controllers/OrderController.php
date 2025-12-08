<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        // VALIDATION
        $request->validate([
            'selected_items' => 'required|string'
        ]);

        $selected = json_decode($request->selected_items, true);

        if (!$selected || count($selected) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $order = null;
        $totalPrice = 0;

        try {
            DB::beginTransaction();

            // BUAT ORDER AWAL
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => 1,
                'total_price' => 0,
                'status' => 'pending'
            ]);

            // AMBIL SEMUA cart_item_id dari frontend
            $cartItemIds = collect($selected)->pluck('id')->toArray();

            // LOCK CART ITEMS
            $cartItems = CartItem::whereIn('cart_item_id', $cartItemIds)
                ->with('product')
                ->lockForUpdate()
                ->get();

            $cartMap = $cartItems->keyBy('cart_item_id');

            $orderItems = [];
            $itemsToDelete = [];

            foreach ($selected as $s) {

                if (!isset($s['id']) || !isset($s['qty'])) continue;

                $cartId = $s['id'];
                $qty = (int)$s['qty'];

                $cartItem = $cartMap->get($cartId);
                if (!$cartItem) continue;

                $product = $cartItem->product;

                // CEK STOK
                if ($qty > $product->stock) {
                    DB::rollBack();
                    return back()->with('error', 'Stok tidak cukup untuk: ' . $product->name);
                }

                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;

                // SIMPAN ORDER ITEM
                $orderItems[] = [
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'qty' => $qty,
                    'price_per_item' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // KURANGI STOK PRODUK
                $product->stock -= $qty;
                $product->save();

                // HAPUS CART ITEM
                $itemsToDelete[] = $cartId;
            }

            OrderItem::insert($orderItems);

            // UPDATE TOTAL + ONGKIR
            $order->total_price = $totalPrice + 10000;
            $order->save();

            // HAPUS CART ITEMS
            CartItem::destroy($itemsToDelete);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            if ($order) {
                $order->delete();
            }

            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }

        return redirect()->route('payment.page', $order->order_id);
    }

    // TRACK
    public function trackList()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('buyer.track.track-list', compact('orders'));
    }

    // CANCEL ORDER
    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'Order tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            $order->status = 'cancelled';
            $order->save();

            foreach ($order->items as $item) {
                Product::where('product_id', $item->product_id)
                    ->increment('stock', $item->qty);
            }
        });

        return back()->with('success', 'Order #' . $order->order_id . ' telah dibatalkan.');
    }

    // COMPLETE ORDER
    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order belum dibayar.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order telah selesai.');
    }

    // DELETE ORDER
    public function deleteOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Tidak diizinkan.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Hanya order completed/cancelled yang dapat dihapus.');
        }

        $order->items()->delete();
        $order->delete();

        return back()->with('success', 'Riwayat order telah dihapus.');
    }
}
