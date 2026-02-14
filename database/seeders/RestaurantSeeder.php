<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample restaurant
        $restaurant = Restaurant::create([
            'name' => 'The Gourmet Kitchen',
            'slug' => 'the-gourmet-kitchen',
            'description' => 'Fine dining experience with fresh, locally sourced ingredients.',
            'phone' => '+1-555-0123',
            'email' => 'info@gourmetkitchen.com',
            'address' => '123 Main Street, Downtown, City 12345',
            'is_active' => true,
            'opening_hours' => [
                'monday' => ['open' => '11:00', 'close' => '22:00'],
                'tuesday' => ['open' => '11:00', 'close' => '22:00'],
                'wednesday' => ['open' => '11:00', 'close' => '22:00'],
                'thursday' => ['open' => '11:00', 'close' => '22:00'],
                'friday' => ['open' => '11:00', 'close' => '23:00'],
                'saturday' => ['open' => '10:00', 'close' => '23:00'],
                'sunday' => ['open' => '10:00', 'close' => '21:00'],
            ],
        ]);

        // Create main menu for the restaurant
        $mainMenu = \App\Models\Menu::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Menu',
            'slug' => 'main-menu',
            'description' => 'Our complete dining menu with all available dishes',
            'is_active' => true,
        ]);

        // Create menu categories
        $appetizers = MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'name' => 'Appetizers',
            'slug' => 'appetizers',
            'description' => 'Start your meal with our delicious appetizers',
            'sort_order' => 1,
        ]);

        $mains = MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'name' => 'Main Courses',
            'slug' => 'main-courses',
            'description' => 'Our signature main dishes',
            'sort_order' => 2,
        ]);

        $desserts = MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'name' => 'Desserts',
            'slug' => 'desserts',
            'description' => 'Sweet endings to your meal',
            'sort_order' => 3,
        ]);

        $beverages = MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'name' => 'Beverages',
            'slug' => 'beverages',
            'description' => 'Refreshing drinks and beverages',
            'sort_order' => 4,
        ]);

        // Create menu items
        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'menu_category_id' => $appetizers->id,
            'name' => 'Caesar Salad',
            'slug' => 'caesar-salad',
            'description' => 'Fresh romaine lettuce with parmesan cheese and croutons',
            'price' => 12.99,
            'preparation_time' => 10,
            'ingredients' => ['romaine lettuce', 'parmesan cheese', 'croutons', 'caesar dressing'],
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'menu_category_id' => $mains->id,
            'name' => 'Grilled Salmon',
            'slug' => 'grilled-salmon',
            'description' => 'Fresh Atlantic salmon with herbs and lemon',
            'price' => 28.99,
            'preparation_time' => 20,
            'ingredients' => ['salmon', 'herbs', 'lemon', 'olive oil'],
            'is_featured' => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'menu_category_id' => $mains->id,
            'name' => 'Beef Tenderloin',
            'slug' => 'beef-tenderloin',
            'description' => 'Premium beef tenderloin with seasonal vegetables',
            'price' => 35.99,
            'preparation_time' => 25,
            'ingredients' => ['beef tenderloin', 'seasonal vegetables', 'red wine sauce'],
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'menu_category_id' => $desserts->id,
            'name' => 'Chocolate Lava Cake',
            'slug' => 'chocolate-lava-cake',
            'description' => 'Warm chocolate cake with molten center',
            'price' => 9.99,
            'preparation_time' => 15,
            'ingredients' => ['dark chocolate', 'butter', 'eggs', 'flour'],
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'menu_id' => $mainMenu->id,
            'menu_category_id' => $beverages->id,
            'name' => 'Fresh Orange Juice',
            'slug' => 'fresh-orange-juice',
            'description' => 'Freshly squeezed orange juice',
            'price' => 4.99,
            'preparation_time' => 5,
            'ingredients' => ['fresh oranges'],
        ]);

        // Create sample users
        $admin = User::create([
            'name' => 'Restaurant Admin',
            'email' => 'admin@gourmetkitchen.com',
            'password' => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
        ]);
        $admin->assignRole('admin');

        $waiter = User::create([
            'name' => 'John Waiter',
            'email' => 'waiter@gourmetkitchen.com',
            'password' => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
        ]);
        $waiter->assignRole('waiter');

        $kitchen = User::create([
            'name' => 'Chef Kitchen',
            'email' => 'kitchen@gourmetkitchen.com',
            'password' => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
        ]);
        $kitchen->assignRole('kitchen');

        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        // Create sample orders for testing waiter and kitchen portals
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->get();
        
        // Create some sample orders with different statuses
        $sampleOrders = [
            [
                'customer_name' => 'John Doe',
                'customer_phone' => '555-0123',
                'customer_email' => 'john@example.com',
                'order_type' => 'dine_in',
                'table_number' => 'T-05',
                'status' => 'pending',
                'notes' => 'No onions please',
                'items' => [
                    ['menu_item_id' => $menuItems->where('name', 'Caesar Salad')->first()->id, 'quantity' => 2],
                    ['menu_item_id' => $menuItems->where('name', 'Grilled Salmon')->first()->id, 'quantity' => 1],
                ]
            ],
            [
                'customer_name' => 'Jane Smith',
                'customer_phone' => '555-0456',
                'customer_email' => 'jane@example.com',
                'order_type' => 'takeaway',
                'status' => 'confirmed',
                'notes' => 'Extra sauce on the side',
                'items' => [
                    ['menu_item_id' => $menuItems->where('name', 'Beef Tenderloin')->first()->id, 'quantity' => 1],
                    ['menu_item_id' => $menuItems->where('name', 'Fresh Orange Juice')->first()->id, 'quantity' => 2],
                ]
            ],
            [
                'customer_name' => 'Mike Johnson',
                'customer_phone' => '555-0789',
                'customer_email' => 'mike@example.com',
                'order_type' => 'dine_in',
                'table_number' => 'T-12',
                'status' => 'preparing',
                'items' => [
                    ['menu_item_id' => $menuItems->where('name', 'Grilled Salmon')->first()->id, 'quantity' => 1],
                    ['menu_item_id' => $menuItems->where('name', 'Caesar Salad')->first()->id, 'quantity' => 1],
                    ['menu_item_id' => $menuItems->where('name', 'Chocolate Lava Cake')->first()->id, 'quantity' => 2],
                ]
            ],
            [
                'customer_name' => 'Sarah Wilson',
                'customer_phone' => '555-0321',
                'customer_email' => 'sarah@example.com',
                'order_type' => 'delivery',
                'delivery_address' => '123 Main St, City, State 12345',
                'status' => 'ready',
                'items' => [
                    ['menu_item_id' => $menuItems->where('name', 'Beef Tenderloin')->first()->id, 'quantity' => 2],
                    ['menu_item_id' => $menuItems->where('name', 'Chocolate Lava Cake')->first()->id, 'quantity' => 1],
                ]
            ]
        ];

        foreach ($sampleOrders as $orderData) {
            $subtotal = 0;
            $orderItems = $orderData['items'];
            unset($orderData['items']);

            // Calculate subtotal
            foreach ($orderItems as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                $subtotal += $menuItem->price * $item['quantity'];
            }

            $taxAmount = $subtotal * 0.08; // 8% tax
            $deliveryFee = $orderData['order_type'] === 'delivery' ? 5.00 : 0;
            $totalAmount = $subtotal + $taxAmount + $deliveryFee;

            $order = Order::create(array_merge($orderData, [
                'restaurant_id' => $restaurant->id,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'estimated_ready_at' => now()->addMinutes(rand(15, 45)),
            ]));

            // Create order items
            foreach ($orderItems as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                $totalPrice = $menuItem->price * $item['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'item_price' => $menuItem->price,
                    'quantity' => $item['quantity'],
                    'total_price' => $totalPrice,
                    'status' => $order->status === 'preparing' ? 'preparing' : 'pending',
                ]);
            }
        }
    }
}