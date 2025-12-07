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
                'icon' => 'fa-shirt'
            ],
            [
                'name' => 'Aksesoris',
                'slug' => 'aksesoris',
                'icon' => 'fa-gem'
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'icon' => 'fa-bolt'
            ],
            [
                'name' => 'Buku',
                'slug' => 'buku',
                'icon' => 'fa-book'
            ],
            [
                'name' => 'Lain-lain',
                'slug' => 'lain-lain',
                'icon' => 'fa-ellipsis'
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
