<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // View Cart
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->with('items.product')
                    ->first();

        if (!$cart) {
            return view('buyer.keranjang.buyerKeranjang', [
                'items' => [],
                'summary' => ['shipping' => 10000]
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

    // Add to cart
    public function add(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $product = Product::findOrFail($request->product_id);

        CartItem::updateOrCreate(
            [
                'cart_id' => $cart->cart_id,   // FIX DI SINI
                'product_id' => $request->product_id
            ],
            [
                'price_per_item' => $product->price,
                'qty' => \DB::raw('qty + 1')
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Added to Cart');
    }

    public function addAjax(Request $request)
    {
        $productId = $request->product_id;

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->cart_id)  // FIX DI SINI
                            ->where('product_id', $productId)
                            ->first();

        $isCurrentlyInCart = (bool) $cartItem;

        if ($isCurrentlyInCart) {

            $cartItem->delete();
            $action = 'removed';

        } else {

            $product = Product::find($productId);

            CartItem::create([
                'cart_id' => $cart->cart_id,   // FIX JUGA DI SINI
                'product_id' => $productId,
                'price_per_item' => $product->price,
                'qty' => 1,
            ]);

            $action = 'added';
        }

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'is_active' => !$isCurrentlyInCart
        ]);
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->item_id);

        // Ambil stock produk
        $stock = $item->product->stock;

        if ($request->qty > $stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stock tidak cukup!'
            ], 400);
        }

        // Update qty jika valid
        $item->qty = $request->qty;
        $item->save();

        return response()->json(['status' => 'success']);
    }

    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer'
        ]);

        CartItem::where('cart_item_id', $request->item_id)->delete();

        return response()->json(['status' => 'deleted']);
    }

}
