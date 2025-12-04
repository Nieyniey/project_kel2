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
        // Use 'first()' instead of 'firstOrFail()' if a user might not have a wishlist yet
        $wishlist = Wishlist::where('user_id', Auth::id())
                            ->with('items.product')
                            ->first();

        // Pass an empty collection if wishlist doesn't exist yet
        $items = $wishlist ? $wishlist->items : collect();
        
        return view('buyer.wishlist', compact('items')); 
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
}