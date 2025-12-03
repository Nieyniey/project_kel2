<?php
namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        return [
            'store_name' => $this->faker->company,
            // 'user_id' diisi di Seeder
        ];
    }
}