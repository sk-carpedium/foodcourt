<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@admin.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create Kitchen Staff User
        $kitchen = User::firstOrCreate(
            ['email' => 'kitchen@admin.com'],
            [
                'name' => 'Kitchen Staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $kitchen->assignRole('kitchen');

        // Create Waiter User
        $waiter = User::firstOrCreate(
            ['email' => 'waiter@admin.com'],
            [
                'name' => 'Waiter',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $waiter->assignRole('waiter');

        // Create Cashier Users (2 shifts)
        $cashier1 = User::firstOrCreate(
            ['email' => 'cashier1@admin.com'],
            [
                'name' => 'Cashier - Morning Shift',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $cashier1->assignRole('cashier');

        $cashier2 = User::firstOrCreate(
            ['email' => 'cashier2@admin.com'],
            [
                'name' => 'Cashier - Evening Shift',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $cashier2->assignRole('cashier');

        // Create Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@admin.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');

        echo "Users created successfully:\n";
        echo "- Super Admin: superadmin@admin.com (password: password)\n";
        echo "- Admin: admin@admin.com (password: password)\n";
        echo "- Kitchen: kitchen@admin.com (password: password)\n";
        echo "- Waiter: waiter@admin.com (password: password)\n";
        echo "- Cashier 1 (Morning): cashier1@admin.com (password: password)\n";
        echo "- Cashier 2 (Evening): cashier2@admin.com (password: password)\n";
        echo "- Customer: customer@admin.com (password: password)\n";
    }
}