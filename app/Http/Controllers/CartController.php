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

        $item = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $request->product_id],
            ['price_per_item' => $product->price, 'qty' => \DB::raw('qty + 1')]
        );

        return redirect()->route('cart.index')->with('success', 'Added to Cart');
    }

    public function addAjax(Request $request)
    {
        $productId = $request->product_id;
        
        // 1. Get/Create the cart header
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        // 2. Explicitly check for the CartItem
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();
                            
        $isCurrentlyInCart = (bool) $cartItem; // Check if the item exists

        if ($isCurrentlyInCart) {
            // --- REMOVAL PATH (Second Click) ---
            // If item exists, delete it. This is the correct action to remove.
            $cartItem->delete(); 
            $action = 'removed';
        } else {
            // --- ADDITION PATH (First Click) ---
            // Ensure product exists before adding
            $product = Product::find($productId); 

            if (!$product) {
                return response()->json(['status' => 'error', 'message' => 'Product not found.'], 404);
            }
            
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price_per_item' => $product->price,
                'qty' => 1,
            ]);
            $action = 'added';
        }

        // 3. Return the new state (which is the opposite of the starting state)
        return response()->json([
            'status' => 'success', 
            'action' => $action,
            'is_active' => !$isCurrentlyInCart // New state is the opposite of the old state
        ]);
    }
}
