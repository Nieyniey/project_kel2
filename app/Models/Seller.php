<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    // Tetapkan primary key
    protected $primaryKey = 'seller_id';

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
