<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke tabel 'chats' (Menunjukkan pesan ini milik room chat mana)
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            
            // Foreign Key ke tabel 'users' (Menunjukkan siapa pengirim pesan ini)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            
            $table->text('content'); // Isi pesan
            $table->boolean('is_read')->default(false); // Untuk fitur notifikasi/pesan dibaca
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};