<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 10000, 5000000), 
            'stock' => $this->faker->numberBetween(0, 100),
            'is_sale' => $this->faker->boolean(20), // 20% kemungkinan diskon
            'image_path' => 'products/dummy-' . $this->faker->numberBetween(1, 10) . '.jpg',
        ];
    }
}