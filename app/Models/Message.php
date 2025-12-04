<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'sender_id', 'content', 'is_read'];

    /**
     * Relasi ke chat (Many-to-One: Banyak pesan milik satu chat room)
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relasi ke user (sender)
     */
    public function sender(): BelongsTo
    {
        // Pesan belongs to sender (user)
        return $this->belongsTo(User::class, 'sender_id');
    }
}