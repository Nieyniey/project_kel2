<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    
    // Primary key
    protected $primaryKey = 'seller_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'store_name',
        'description',
        'instagram',
        'status',
    ];

    /**
     * Relasi Seller -> User (One to One)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi Seller -> Product (One to Many)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
}
