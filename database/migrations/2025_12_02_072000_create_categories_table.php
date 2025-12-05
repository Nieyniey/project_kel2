<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id'); // Primary Key
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL yang cantik (misal: elektronik-murah)
            $table->string('icon')->nullable(); // icon kategori
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};