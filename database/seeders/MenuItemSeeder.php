<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Chicken Biryani', 'description' => 'Aromatic basmati rice with tender chicken and spices', 'price' => 450, 'preparation_time' => 20],
            ['name' => 'Beef Burger', 'description' => 'Juicy beef patty with cheese, lettuce and tomato', 'price' => 550, 'preparation_time' => 15],
            ['name' => 'Margherita Pizza', 'description' => 'Classic pizza with mozzarella, tomato sauce and basil', 'price' => 800, 'preparation_time' => 18],
            ['name' => 'Chicken Karahi', 'description' => 'Traditional chicken karahi cooked with fresh tomatoes and green chilies', 'price' => 950, 'preparation_time' => 25],
            ['name' => 'Club Sandwich', 'description' => 'Triple-decker sandwich with chicken, egg, lettuce and mayo', 'price' => 400, 'preparation_time' => 10],
            ['name' => 'Seekh Kebab', 'description' => 'Grilled minced beef kebabs with herbs and spices', 'price' => 350, 'preparation_time' => 15],
            ['name' => 'Chicken Shawarma', 'description' => 'Seasoned chicken wrapped in pita with garlic sauce', 'price' => 300, 'preparation_time' => 10],
            ['name' => 'Mutton Pulao', 'description' => 'Fragrant rice cooked with tender mutton pieces', 'price' => 600, 'preparation_time' => 25],
            ['name' => 'Fish and Chips', 'description' => 'Crispy battered fish with golden fries', 'price' => 650, 'preparation_time' => 15],
            ['name' => 'Chocolate Brownie', 'description' => 'Warm fudgy brownie served with vanilla ice cream', 'price' => 250, 'preparation_time' => 5],
        ];

        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $menu = Menu::where('restaurant_id', $restaurant->id)->first();
            $category = MenuCategory::where('restaurant_id', $restaurant->id)->first();

            if (!$menu || !$category) {
                continue;
            }

            foreach ($items as $index => $item) {
                MenuItem::create([
                    'restaurant_id' => $restaurant->id,
                    'menu_id' => $menu->id,
                    'menu_category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => \Illuminate\Support\Str::slug($item['name']) . '-r' . $restaurant->id,
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'preparation_time' => $item['preparation_time'],
                    'is_available' => true,
                    'is_featured' => $index < 3,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
