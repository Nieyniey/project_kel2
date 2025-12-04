<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'stock',
        'image_path',
    ];


    public function seller(){
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
