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
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $product = Product::findOrFail($request->product_id);

        CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            [
                'price_per_item' => $product->price,
                'qty' => \DB::raw('qty + 1')
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Added to Cart');
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
            // Remove item
            $existing->delete();
            return response()->json([
                'status' => 'success',
                'action' => 'removed',
                'is_active' => false
            ]);
        }

        // Add item
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

        $item = CartItem::where('id', $request->item_id)
                        ->whereHas('cart', function($q){
                            $q->where('user_id', Auth::id());
                        })
                        ->firstOrFail();

        $item->delete();

        return response()->json(['status' => 'deleted']);
    }
}
