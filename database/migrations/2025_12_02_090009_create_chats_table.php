<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            
            // user1_id dan user2_id adalah ID user dari tabel 'users'
            // Kita gunakan 'user1' untuk Buyer dan 'user2' untuk Seller (atau sebaliknya, yang penting ada 2)
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade');
            
            // Untuk memastikan hanya ada SATU chat room antara pasangan user yang sama
            $table->unique(['user1_id', 'user2_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};