<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@henzosushi.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
        ]);
        $admin->assignRole('admin');

        $chef = User::create([
            'name' => 'Chef Tanaka',
            'email' => 'chef@henzosushi.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
        ]);
        $chef->assignRole('chef');

        $delivery = User::create([
            'name' => 'Delivery Guy',
            'email' => 'delivery@henzosushi.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
        ]);
        $delivery->assignRole('delivery');

        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567893',
        ]);
        $customer->assignRole('customer');

        // Create sample categories
        $categories = [
            [
                'name' => 'Nigiri',
                'description' => 'Traditional sushi with fish on rice',
                'sort_order' => 1,
            ],
            [
                'name' => 'Maki Rolls',
                'description' => 'Rolled sushi with seaweed',
                'sort_order' => 2,
            ],
            [
                'name' => 'Sashimi',
                'description' => 'Fresh raw fish slices',
                'sort_order' => 3,
            ],
            [
                'name' => 'Appetizers',
                'description' => 'Japanese appetizers and sides',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create sample products
        $products = [
            // Nigiri
            [
                'name' => 'Salmon Nigiri',
                'description' => 'Fresh salmon on seasoned rice',
                'price' => 3.50,
                'preparation_time' => 5,
                'category_id' => 1,
            ],
            [
                'name' => 'Tuna Nigiri',
                'description' => 'Premium tuna on seasoned rice',
                'price' => 4.00,
                'preparation_time' => 5,
                'category_id' => 1,
            ],
            [
                'name' => 'Eel Nigiri',
                'description' => 'Grilled eel with sweet sauce',
                'price' => 4.50,
                'preparation_time' => 8,
                'category_id' => 1,
            ],
            
            // Maki Rolls
            [
                'name' => 'California Roll',
                'description' => 'Crab, avocado, and cucumber',
                'price' => 8.00,
                'preparation_time' => 10,
                'category_id' => 2,
            ],
            [
                'name' => 'Spicy Tuna Roll',
                'description' => 'Spicy tuna with cucumber',
                'price' => 9.00,
                'preparation_time' => 12,
                'category_id' => 2,
            ],
            [
                'name' => 'Dragon Roll',
                'description' => 'Eel, cucumber, and avocado topped with eel sauce',
                'price' => 12.00,
                'preparation_time' => 15,
                'category_id' => 2,
            ],
            
            // Sashimi
            [
                'name' => 'Salmon Sashimi',
                'description' => 'Fresh salmon slices',
                'price' => 12.00,
                'preparation_time' => 3,
                'category_id' => 3,
            ],
            [
                'name' => 'Tuna Sashimi',
                'description' => 'Premium tuna slices',
                'price' => 15.00,
                'preparation_time' => 3,
                'category_id' => 3,
            ],
            
            // Appetizers
            [
                'name' => 'Miso Soup',
                'description' => 'Traditional Japanese soup',
                'price' => 3.00,
                'preparation_time' => 5,
                'category_id' => 4,
            ],
            [
                'name' => 'Edamame',
                'description' => 'Steamed soybeans with salt',
                'price' => 4.00,
                'preparation_time' => 3,
                'category_id' => 4,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
