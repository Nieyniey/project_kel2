<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil semua seeder secara berurutan
        $this->call([
            // 1. Core Data (Users, Sellers, Products, Carts)
            ECommerceSeeder::class, 
            
            // 2. Transactions (Orders, Payments, bergantung pada User/Product)
            TransactionSeeder::class, 
            
            // 3. Communications (Chats, bergantung pada User)
            ChatSeeder::class, 
        ]);
    }
}