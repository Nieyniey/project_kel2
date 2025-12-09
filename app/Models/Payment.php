<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <<< IMPORT INI
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory; 

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'method',
        'status',
        'paid_at'
    ];

    public function order(): BelongsTo
    {
        // Pastikan FK order_id
        return $this->belongsTo(Order::class, 'order_id');
    }
}