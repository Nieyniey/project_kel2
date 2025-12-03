<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $primaryKey = 'wishlist_id';

    public function items()
    {
        return $this->hasMany(WishlistItem::class, 'wishlist_id');
    }
}
