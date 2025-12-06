<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $primaryKey = 'wishlist_item_id';
    protected $table = 'wishlist_items';

    protected $fillable = [
        'wishlist_item_id',      // <<< WAJIB ADA
        'wishlist_id',      // <<< WAJIB ADA
        'product_id',   // <<< WAJIB ADA
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
