<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id'; // kalau pakai ini

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price_per_item'
    ];

    public $timestamps = true; // kalau tabel punya created_at & updated_at

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

}
