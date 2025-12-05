<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category; // Import Category
use App\Models\CartItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ECommerceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); 

        // 0. BUAT KATEGORI (Wajib ada sebelum produk)
        $categories = Category::factory(10)->create(); // Buat 10 kategori dummy

        // 1. BUAT AKUN TEST
        User::create(['name' => 'Admin Test', 'email' => 'admin@test.com', 'password' => Hash::make('password')]);
        User::create(['name' => 'Test Buyer', 'email' => 'buyer@test.com', 'password' => Hash::make('password')]);
        $sellerTestUser = User::create(['name' => 'Test Seller', 'email' => 'seller@test.com', 'password' => Hash::make('password')]);

        Seller::factory()->create([
            'user_id' => $sellerTestUser->id,
            'store_name' => 'Toko Uji Coba',
            'status' => 'active',
        ]);
        
        // 2. BUAT DATA DUMMY MASSAL
        User::factory(50)->create();
        $sellerUsers = User::factory(10)->create(); 

        $sellerUsers->each(function (User $user) {
            Seller::factory()->create(['user_id' => $user->id]);
        });
        
        $sellers = Seller::all();

        // 3. BUAT PRODUK (Dengan Kategori)
        $productsCollection = collect([]);
        $sellers->each(function (Seller $seller) use (&$productsCollection, $faker, $categories) { 
            
            // Buat produk untuk seller ini
            $products = Product::factory($faker->numberBetween(15, 30))->make([
                'seller_id' => $seller->seller_id,
            ]);

            // Assign kategori acak ke setiap produk dan simpan
            $products->each(function($product) use ($categories) {
                $product->category_id = $categories->random()->category_id;
                $product->save();
            });

            $productsCollection = $productsCollection->merge($products);
        });

        // 4. BUAT KERANJANG BELANJA
        $buyerUsers = User::whereDoesntHave('seller')->get();
        
        $buyerUsers->each(function (User $user) use ($productsCollection, $faker) { 
            if ($productsCollection->isNotEmpty() && $faker->boolean(40)) { 
                $cart = Cart::factory()->create(['user_id' => $user->id]);
                $cartId = $cart->id; 

                $randomProducts = $productsCollection->random($faker->numberBetween(1, min(5, $productsCollection->count())));
                
                $randomProducts->each(function ($product) use ($cartId, $faker) { 
                    CartItem::create([
                        'cart_id' => $cartId, 
                        'product_id' => $product->product_id,
                        'qty' => $faker->numberBetween(1, 3), 
                        'price_per_item' => $product->price,
                    ]);
                });
            }
        });
    }
}