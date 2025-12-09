<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker; // <<< Import Faker

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Inisialisasi Faker
        $faker = Faker::create();

        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Buat 50 Pesanan Dummy
        Order::factory(50)->make()
            ->each(function (Order $order) use ($users, $products, $faker) {
            
            $order->user_id = $users->random()->id;
            $order->total_price = 0; 
            $order->save();
            
            $orderId = $order->order_id ?? $order->id; 

            $total = 0;
            
            $orderItemsCount = $faker->numberBetween(1, min(4, $products->count()));
            $orderItems = $products->random($orderItemsCount);

            $orderItems->each(function (Product $product) use ($orderId, $order, &$total, $faker) {
                // Gunakan $faker
                $qty = $faker->numberBetween(1, 3);
                $price = $product->price;

                DB::table('order_items')->insert([
                    'order_id' => $orderId, 
                    'product_id' => $product->product_id, 
                    'qty' => $qty,
                    'price_per_item' => $price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at,
                ]);
                
                $total += $qty * $price;
            });
            
            // Update total price di tabel order
            $order->total_price = $total;
            $order->save();

            // Buat record Pembayaran 
            if ($order->status === 'completed' || $order->status === 'shipped') {
                Payment::factory()->create([
                    'order_id' => $orderId, 
                    'status' => 'completed',
                    'method' => $order->payment_method
                ]);
            }
        });
    }
}