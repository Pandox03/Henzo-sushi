<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DeliveryUserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Create delivery role if it doesn't exist
        $deliveryRole = Role::firstOrCreate(['name' => 'delivery']);

        // Create delivery users
        $deliveryUsers = [
            [
                'name' => 'Ahmed Delivery',
                'email' => 'ahmed.delivery@henzosushi.com',
                'phone' => '0612345678',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Youssef Delivery',
                'email' => 'youssef.delivery@henzosushi.com',
                'phone' => '0623456789',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Karim Delivery',
                'email' => 'karim.delivery@henzosushi.com',
                'phone' => '0634567890',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($deliveryUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            // Assign delivery role
            if (!$user->hasRole('delivery')) {
                $user->assignRole($deliveryRole);
            }
        }
    }
}