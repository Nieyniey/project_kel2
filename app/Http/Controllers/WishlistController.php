<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        // 1. Initialize $wishlistItems as an empty collection
        $wishlistItems = collect();

        // 2. Fetch the user's wishlist
        // We use with('items.product') to load the related items and products efficiently
        $wishlist = Wishlist::where('user_id', Auth::id())
                            ->with('items.product')
                            ->first();

        // 3. If a wishlist is found, assign its items to the variable
        if ($wishlist) {
            // Note: $wishlist->items will be a Collection of WishlistItem models
            $wishlistItems = $wishlist->items; 
        }
        
        return view('buyer.wishlist', compact('wishlistItems')); 
    }

    public function add(Request $request)
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);

        WishlistItem::firstOrCreate([
            'wishlist_id' => $wishlist->id,
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Added to Wishlist');
    }

    /**
     * Remove an item from the wishlist (Unliking the filled heart).
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $wishlist = Wishlist::where('user_id', Auth::id())->first();

        if ($wishlist) {
            $deleted = WishlistItem::where('wishlist_id', $wishlist->id)
                                   ->where('product_id', $request->product_id)
                                   ->delete();

            if ($deleted) {
                 return back()->with('success', 'Product removed from your Wishlist.');
            }
        }
        
        return back()->with('error', 'Product was not found in your Wishlist.');
    }

    public function addAjax(Request $request)
    {
        $productId = $request->product_id;
        
        // 1. Get/Create the wishlist header
        $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);
        
        // 2. Explicitly check for the WishlistItem
        $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
                                    ->where('product_id', $productId)
                                    ->first();

        $isCurrentlyInWishlist = (bool) $wishlistItem;

        if ($isCurrentlyInWishlist) {
            // --- REMOVAL PATH (Second Click) ---
            // If item exists, delete it.
            $wishlistItem->delete(); 
            $action = 'removed';
        } else {
            // --- ADDITION PATH (First Click) ---
            WishlistItem::create([
                'wishlist_id' => $wishlist->id,
                'product_id' => $productId,
            ]);
            $action = 'added';
        }

        // 3. Return the new state
        return response()->json([
            'status' => 'success', 
            'action' => $action,
            'is_active' => !$isCurrentlyInWishlist // New state is the opposite of the old state
        ]);
    }
}