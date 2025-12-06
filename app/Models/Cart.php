<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    // Pastikan Primary Key benar (default 'id'). 
    // Jika di migration Anda menggunakan $table->id('cart_id'), ubah ini jadi 'cart_id'
    protected $primaryKey = 'id'; 
    protected $table = 'carts';

    protected $fillable = [
        'user_id', // <<< WAJIB ADA AGAR BISA DIISI LEWAT CREATE()
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}