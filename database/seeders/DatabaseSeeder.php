<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil semua seeder secara berurutan
        $this->call([
            // 1. Category harus duluan
            CategorySeeder::class,

            // 2. Core Data (Users, Sellers, Products, Carts)
            ECommerceSeeder::class,

            // 3. Transactions (Orders, Payments)
            TransactionSeeder::class,

            // 4. Communications (Chats)
            ChatSeeder::class,
        ]);
    }
}
