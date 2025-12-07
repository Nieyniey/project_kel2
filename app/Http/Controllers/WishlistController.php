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
        $wishlistItems = collect();

        $wishlist = Wishlist::where('user_id', Auth::id())
                            ->with('items.product')
                            ->first();

        if ($wishlist) {
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
        
        $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);
        
        $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
                                    ->where('product_id', $productId)
                                    ->first();

        $isCurrentlyInWishlist = (bool) $wishlistItem;

        if ($isCurrentlyInWishlist) {
            $wishlistItem->delete(); 
            $action = 'removed';
        } else {
            WishlistItem::create([
                'wishlist_id' => $wishlist->id,
                'product_id' => $productId,
            ]);
            $action = 'added';
        }

        return response()->json([
            'status' => 'success', 
            'action' => $action,
            'is_active' => !$isCurrentlyInWishlist 
        ]);
    }
}