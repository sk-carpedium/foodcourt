<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NineRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            $restaurant = Restaurant::create([
                'name' => "Restaurant-{$i}",
                'slug' => "restaurant-{$i}",
                'description' => "Welcome to Restaurant-{$i}",
                'phone' => '+1-555-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => "info@restaurant-{$i}.com",
                'address' => "{$i}00 Food Street, City",
                'is_active' => true,
            ]);

            $menu = Menu::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Default Menu',
                'slug' => "default-menu-r{$i}",
                'description' => 'Main menu',
                'is_active' => true,
            ]);

            MenuCategory::create([
                'restaurant_id' => $restaurant->id,
                'menu_id' => $menu->id,
                'name' => 'General',
                'slug' => "general-r{$i}",
                'sort_order' => 1,
            ]);

            // Kitchen user per restaurant
            $kitchen = User::create([
                'name' => "Kitchen R{$i}",
                'email' => "kitchen@r{$i}.com",
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant->id,
                'email_verified_at' => now(),
            ]);
            $kitchen->assignRole('kitchen');
        }
    }
}
