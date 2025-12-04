<?php
namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'total_price' => $this->faker->randomFloat(2, 50000, 5000000), 
            
            // 🛑 PERBAIKAN: Hapus 'processing', samakan dengan migration
            // Migration: ['pending', 'paid', 'shipped', 'completed', 'cancelled']
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
            
            // Pastikan payment_method juga sudah benar
            'payment_method' => $this->faker->randomElement(['transfer', 'cod', 'ewallet']), 
        ];
    }
}