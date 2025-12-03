<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem; // <<< PASTIKAN MODEL CARTITEM SUDAH DIIMPORT
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ECommerceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); 

        // 1. BUAT AKUN TEST KHUSUS UNTUK FRONT-END
        
        User::create(['name' => 'Admin Test', 'email' => 'admin@test.com', 'password' => Hash::make('password')]);
        User::create(['name' => 'Test Buyer', 'email' => 'buyer@test.com', 'password' => Hash::make('password')]);
        $sellerTestUser = User::create(['name' => 'Test Seller', 'email' => 'seller@test.com', 'password' => Hash::make('password')]);

        Seller::factory()->create([
            'user_id' => $sellerTestUser->id,
            'store_name' => 'Toko Uji Coba',
        ]);
        
        // 2. BUAT DATA DUMMY MASSAL
        User::factory(50)->create();
        $sellerUsers = User::factory(10)->create(); 

        $sellerUsers->each(function (User $user) {
            Seller::factory()->create([
                'user_id' => $user->id,
            ]);
        });
        
        $sellers = Seller::all();

        // 3. BUAT PRODUK untuk setiap Seller
        $productsCollection = collect([]);
        $sellers->each(function (Seller $seller) use (&$productsCollection, $faker) { 
            $products = Product::factory($faker->numberBetween(15, 30))->create([
                'seller_id' => $seller->seller_id,
            ]);
            $productsCollection = $productsCollection->merge($products);
        });

        // 4. BUAT KERANJANG BELANJA (Cart)
        $buyerUsers = User::whereDoesntHave('seller')->get();
        
        // HAPUS: $allCartItems = []; // Array untuk menampung semua item yang akan dimasukkan

        $buyerUsers->each(function (User $user) use ($productsCollection, $faker) { 
            
            if ($productsCollection->isNotEmpty() && $faker->boolean(40)) { 
                
                // 1. BUAT CART
                $cart = Cart::factory()->create(['user_id' => $user->id]);
                $cartId = $cart->id; 

                $randomProducts = $productsCollection->random($faker->numberBetween(1, min(5, $productsCollection->count())));
                
                // 2. MASUKKAN ITEM CART SATU PER SATU MENGGUNAKAN MODEL CREATE
                $randomProducts->each(function ($product) use ($cartId, $faker) { 
                    
                    // Kita asumsikan Anda memiliki Model CartItem
                    // Jika Anda tidak memiliki CartItem Model, buatlah: php artisan make:model CartItem -f
                    
                    \App\Models\CartItem::create([
                        'cart_id' => $cartId, 
                        'product_id' => $product->product_id,
                        'qty' => $faker->numberBetween(1, 3), 
                        'price_per_item' => $product->price,
                    ]);
                });
            }
        });
        
        // HAPUS: 
        // if (!empty($allCartItems)) {
        //     DB::table('cart_items')->insert($allCartItems); // Mengganti ini
        // }
    }
}