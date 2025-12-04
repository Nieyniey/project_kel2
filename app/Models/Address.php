<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'user_id',
        'label',
        'address_text',
        'city',
        'postal_code',
        'is_default'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
