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
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 10000, 1000000), 
            'stock' => $this->faker->numberBetween(10, 200),
            'image_path' => 'products/' . $this->faker->uuid . '.jpg',
        ];
    }
}