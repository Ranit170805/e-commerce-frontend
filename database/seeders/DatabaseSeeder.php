<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $categories = collect([
            'Electronics',
            'Fashion',
            'Home',
            'Beauty',
        ])->mapWithKeys(function ($name) {
            return [$name => Category::updateOrCreate(['name' => $name])];
        });

        $products = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'Comfortable Bluetooth headphones with clear sound and long battery life.',
                'price' => 49.99,
                'stock' => 25,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track workouts, notifications, and daily activity from your wrist.',
                'price' => 89.00,
                'stock' => 18,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Classic T-Shirt',
                'description' => 'Soft cotton everyday t-shirt with a clean fit.',
                'price' => 14.50,
                'stock' => 60,
                'category' => 'Fashion',
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight shoes for daily walks, gym sessions, and casual wear.',
                'price' => 59.90,
                'stock' => 32,
                'category' => 'Fashion',
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'Adjustable LED desk lamp for study, work, and reading.',
                'price' => 22.75,
                'stock' => 20,
                'category' => 'Home',
            ],
            [
                'name' => 'Ceramic Mug Set',
                'description' => 'Minimal ceramic mugs for coffee, tea, and kitchen display.',
                'price' => 18.00,
                'stock' => 40,
                'category' => 'Home',
            ],
            [
                'name' => 'Face Care Kit',
                'description' => 'Simple skincare essentials for a fresh daily routine.',
                'price' => 35.25,
                'stock' => 15,
                'category' => 'Beauty',
            ],
            [
                'name' => 'Matte Lipstick',
                'description' => 'Smooth matte lipstick with rich color and comfortable wear.',
                'price' => 9.99,
                'stock' => 45,
                'category' => 'Beauty',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'category_id' => $categories[$product['category']]->id,
                ]
            );
        }
    }
}
