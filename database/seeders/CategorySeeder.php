<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'icon' => 'bi-handbag'
            ],
            [
                'name' => 'Aksesoris',
                'slug' => 'aksesoris',
                'icon' => 'bi-gem'
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'icon' => 'bi-phone'
            ],
            [
                'name' => 'Buku dan Media',
                'slug' => 'buku',
                'icon' => 'bi-journals'
            ],
            [
                'name' => 'Lain-lain',
                'slug' => 'lain-lain',
                'icon' => 'bi-three-dots'
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
