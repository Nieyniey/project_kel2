<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';   // PK sesuai ERD
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'is_sale',
        'image_path',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
