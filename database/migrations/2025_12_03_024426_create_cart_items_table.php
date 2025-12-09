<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id'); 

            $table->foreignId('cart_id')
                  ->constrained('carts') 
                  ->onDelete('cascade');
                  
            // Tambahkan FK ke Products
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');

            $table->unsignedSmallInteger('qty');
            $table->decimal('price_per_item', 10, 2);
            
            // Tambahkan unique constraint untuk mencegah duplikasi item produk di cart yang sama
            $table->unique(['cart_id', 'product_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};