<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $item = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $request->product_id],
            ['qty' => \DB::raw('qty + 1')]
        );

        return redirect()->route('cart.index')->with('success', 'Added to Cart');
    }
}
