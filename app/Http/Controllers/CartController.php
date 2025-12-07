<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show cart page
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->with('items.product')
                    ->first();

        if (!$cart) {
            return view('buyer.keranjang.buyerKeranjang', [
                'items' => [],
                'summary' => [
                    'shipping' => 10000,
                ]
            ]);
        }

        $items = $cart->items;
        $subtotal = $items->sum(fn($item) => $item->product->price * $item->qty);
        $shipping = 10000;

        $summary = [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];

        return view('buyer.keranjang.buyerKeranjang', compact('items', 'summary'));
    }


    /**
     * Add item to cart (Non-AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // CEK STOK
        if ($request->qty > $product->stock) {
            return back()->with('error', 'Stock tidak cukup');
        }

        // GET cart user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Cek kalau item sudah ada
        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->product_id)
                        ->first();

        if ($item) {

            // cek lagi total qty kalau ditambah
            if ($item->qty + $request->qty > $product->stock) {
                return back()->with('error', 'Stock tidak cukup untuk jumlah yang diminta');
            }

            // kalau stok cukup → tambah qty
            $item->qty += $request->qty;
            $item->save();

        } else {

            // item belum ada → buat baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->product_id,
                'price_per_item' => $product->price,
                'qty' => $request->qty
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }


    /**
     * Add/remove item from cart via AJAX (toggle)
     */
    public function addAjax(Request $request)
    {
        $productId = $request->product_id;
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $existing = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'status' => 'success',
                'action' => 'removed',
                'is_active' => false
            ]);
        }

        $product = Product::findOrFail($productId);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'price_per_item' => $product->price,
            'qty' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'action' => 'added',
            'is_active' => true
        ]);
    }


    /**
     * Update quantity
     */
    public function updateQty(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        $item = CartItem::where('id', $request->item_id)
                        ->whereHas('cart', function($q){
                            $q->where('user_id', Auth::id());
                        })
                        ->firstOrFail();

        $product = Product::findOrFail($item->product_id);

        // CEK STOK
        if ($request->qty > $product->stock) {
            return response()->json(['status' => 'error', 'message' => 'Stock tidak cukup']);
        }

        $item->qty = $request->qty;
        $item->save();

        return response()->json(['status' => 'success']);
    }


    /**
     * Delete cart item
     */
    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer'
        ]);

        CartItem::where('cart_item_id', $request->item_id)->delete();

        return response()->json(['status' => 'deleted']);
    }
}
