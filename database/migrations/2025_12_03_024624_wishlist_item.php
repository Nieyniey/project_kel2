<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id('wishlist_item_id');
            $table->foreignId('wishlist_id')
                  ->constrained('wishlists') 
                  ->onDelete('cascade');
                  

            $table->foreignId('product_id')
                  ->constrained('products', 'product_id') 
                  ->onDelete('cascade');
            
            $table->unique(['wishlist_id', 'product_id']); // Item wishlist tidak boleh ganda
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
