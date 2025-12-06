<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    // Jika migration cart_items pakai $table->id('cart_item_id'), uncomment baris bawah:
    protected $primaryKey = 'cart_item_id'; 
    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',      // <<< WAJIB ADA
        'product_id',   // <<< WAJIB ADA
        'qty',
        'price_per_item',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id'); // Pastikan FK cart_id
    }

    public function product(): BelongsTo
    {
        // Pastikan FK product_id
        return $this->belongsTo(Product::class, 'product_id'); 
    }
}