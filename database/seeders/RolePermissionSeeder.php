<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Order permissions
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'accept orders',
            'prepare orders',
            'deliver orders',
            
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Admin permissions
            'view dashboard',
            'manage settings',
            'view reports',
            'track deliveries',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $chefRole = Role::create(['name' => 'chef']);
        $deliveryRole = Role::create(['name' => 'delivery']);
        $customerRole = Role::create(['name' => 'customer']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $chefRole->givePermissionTo([
            'view orders',
            'accept orders',
            'prepare orders',
            'view products',
            'view categories',
        ]);
        
        $deliveryRole->givePermissionTo([
            'view orders',
            'deliver orders',
            'track deliveries',
        ]);
        
        $customerRole->givePermissionTo([
            'create orders',
            'view orders',
        ]);
    }
}
