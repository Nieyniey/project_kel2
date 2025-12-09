<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id(); // Primary Key untuk tabel wishlists

            $table->foreignId('user_id')
                  ->constrained('users') 
                  ->onDelete('cascade');

            $table->timestamps();
            
            // Tambahkan unique constraint agar setiap user hanya punya 1 wishlist utama
            $table->unique('user_id'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};