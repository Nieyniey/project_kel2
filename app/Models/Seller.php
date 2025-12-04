<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;
    
    // Tetapkan primary key
    protected $primaryKey = 'seller_id';

    protected $fillable = [
        'user_id', 
        'store_name', 
    ];

    // Relasi Seller ke User (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi Seller ke Products (One-to-Many)
    public function products()
    {
        // Pastikan foreign key di tabel 'products' adalah 'seller_id'
        return $this->hasMany(Product::class, 'seller_id');
    }
}
