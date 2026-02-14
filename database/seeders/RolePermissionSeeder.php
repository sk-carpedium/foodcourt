<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Restaurant management
            'view restaurants',
            'create restaurants',
            'edit restaurants',
            'delete restaurants',
            
            // Menu Category management
            'view menu categories',
            'create menu categories',
            'edit menu categories',
            'delete menu categories',
            
            // Menu Item management
            'view menu items',
            'create menu items',
            'edit menu items',
            'delete menu items',
            
            // Order management
            'view orders',
            'create orders',
            'update orders',
            'delete orders',
            'update order status',
            
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Kitchen operations
            'view kitchen orders',
            'update kitchen status',
            
            // Waiter operations
            'take orders',
            'serve orders',
            'view table orders',
            
            // Customer operations
            'place orders',
            'view own orders',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - restaurant management (same as super-admin for restaurant operations)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            // Restaurant management - full access
            'view restaurants',
            'create restaurants',
            'edit restaurants',
            'delete restaurants',
            // Menu management - full access
            'view menu categories',
            'create menu categories',
            'edit menu categories',
            'delete menu categories',
            'view menu items',
            'create menu items',
            'edit menu items',
            'delete menu items',
            // Order management
            'view orders',
            'create orders',
            'update orders',
            'delete orders',
            'update order status',
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
        ]);

        // Kitchen Staff
        $kitchen = Role::firstOrCreate(['name' => 'kitchen']);
        $kitchen->syncPermissions([
            'view kitchen orders',
            'update kitchen status',
            'view orders',
        ]);

        // Waiter
        $waiter = Role::firstOrCreate(['name' => 'waiter']);
        $waiter->syncPermissions([
            'take orders',
            'serve orders',
            'view table orders',
            'view orders',
            'create orders',
            'update order status',
            'view menu items',
            'view menu categories',
        ]);

        // Customer
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([
            'place orders',
            'view own orders',
            'view menu items',
            'view menu categories',
        ]);
    }
}