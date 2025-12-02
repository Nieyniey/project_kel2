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
        $wishlist = Wishlist::where('user_id', Auth::id())
                           ->with('items.product')
                           ->first();

        return view('wishlist.index', compact('wishlist'));
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
}
