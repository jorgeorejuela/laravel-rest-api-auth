<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            ['name' => 'Create Products', 'slug' => 'create-products', 'description' => 'Can create new products'],
            ['name' => 'Read Products', 'slug' => 'read-products', 'description' => 'Can view products'],
            ['name' => 'Update Products', 'slug' => 'update-products', 'description' => 'Can update products'],
            ['name' => 'Delete Products', 'slug' => 'delete-products', 'description' => 'Can delete products'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full access to all resources',
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Can create, read, and update products',
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Can only read products',
        ]);

        // Assign Permissions to Roles
        $adminRole->permissions()->attach(Permission::all());
        $managerRole->permissions()->attach(Permission::whereIn('slug', ['create-products', 'read-products', 'update-products'])->get());
        $userRole->permissions()->attach(Permission::where('slug', 'read-products')->get());

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Manager User
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('manager');

        // Create Regular User
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $regularUser->assignRole('user');

        // Create Sample Products
        $products = [
            [
                'name' => 'Laptop Pro 15',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'stock' => 25,
                'category' => 'Electronics',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with 6 programmable buttons',
                'price' => 29.99,
                'stock' => 150,
                'category' => 'Accessories',
                'user_id' => $manager->id,
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader',
                'price' => 49.99,
                'stock' => 75,
                'category' => 'Accessories',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with blue switches',
                'price' => 89.99,
                'stock' => 50,
                'category' => 'Accessories',
                'user_id' => $manager->id,
            ],
            [
                'name' => '27" Monitor',
                'description' => '4K UHD monitor with HDR support',
                'price' => 399.99,
                'stock' => 30,
                'category' => 'Electronics',
                'user_id' => $admin->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Manager: manager@example.com / password');
        $this->command->info('User: user@example.com / password');
    }
}
