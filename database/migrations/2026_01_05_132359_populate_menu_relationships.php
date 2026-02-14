<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;

return new class extends Migration
{
    public function up(): void
    {
        // Create menus for existing restaurants that don't have them
        $restaurants = Restaurant::whereDoesntHave('menus')->get();
        
        foreach ($restaurants as $restaurant) {
            $menu = Menu::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Main Menu',
                'slug' => 'main-menu-' . $restaurant->id,
                'description' => 'Complete menu for ' . $restaurant->name,
                'is_active' => true,
            ]);
            
            // Update categories to reference the menu
            MenuCategory::where('restaurant_id', $restaurant->id)
                ->whereNull('menu_id')
                ->update(['menu_id' => $menu->id]);
            
            // Update menu items to reference the menu
            MenuItem::where('restaurant_id', $restaurant->id)
                ->whereNull('menu_id')
                ->update(['menu_id' => $menu->id]);
        }
    }

    public function down(): void
    {
        // Remove menu relationships
        MenuCategory::whereNotNull('menu_id')->update(['menu_id' => null]);
        MenuItem::whereNotNull('menu_id')->update(['menu_id' => null]);
        
        // Delete auto-created menus
        Menu::where('name', 'Main Menu')->delete();
    }
};