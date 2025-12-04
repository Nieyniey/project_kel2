<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <<< IMPORT INI
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory; // <<< TAMBAHKAN INI AGAR FACTORY BISA DIPANGGIL

    protected $primaryKey = 'payment_id'; // Sesuaikan jika migration pakai $table->id('payment_id')

    protected $fillable = [
        'order_id',
        'method',
        'status',
    ];

    public function order(): BelongsTo
    {
        // Pastikan FK order_id
        return $this->belongsTo(Order::class, 'order_id');
    }
}