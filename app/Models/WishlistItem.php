<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $primaryKey = 'wishlist_item_id';
    protected $table = 'wishlist_items';

    protected $fillable = [
        'wishlist_item_id',   
        'wishlist_id',    
        'product_id',   
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
