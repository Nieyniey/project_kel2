<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addAjax(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'unauthenticated']);
        }

        // Ambil wishlist user atau buat baru
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id
        ]);

        $productId = $request->product_id;

        // Cek apakah produk sudah ada di wishlist_items
        $existing = WishlistItem::where('wishlist_id', $wishlist->id)
                                ->where('product_id', $productId)
                                ->first();

        if ($existing) {
            // HAPUS → USER MEMBATALKAN FAVORITE
            $existing->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        }

        // TAMBAH → FAVORITE BARU
        WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $productId
        ]);

        return response()->json([
            'status' => 'added'
        ]);
    }
}
