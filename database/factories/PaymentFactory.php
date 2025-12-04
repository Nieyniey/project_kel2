<?php
namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            // 'order_id' diisi di Seeder
            'method' => $this->faker->randomElement(['transfer', 'cod', 'ewallet']),
            'status' => $this->faker->randomElement(['completed', 'failed', 'pending']),
        ];
    }
}