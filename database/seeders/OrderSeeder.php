<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first restaurant
        $restaurant = Restaurant::first();
        
        if (!$restaurant) {
            $this->command->error('No restaurant found. Please run RestaurantSeeder first.');
            return;
        }

        // Get menu items for this restaurant
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->get();
        
        if ($menuItems->isEmpty()) {
            $this->command->error('No menu items found. Please run RestaurantSeeder first.');
            return;
        }

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
            ],
            [
                'customer_name' => 'David Brown',
                'customer_phone' => '555-0654',
                'customer_email' => 'david@example.com',
                'order_type' => 'dine_in',
                'table_number' => 'T-08',
                'status' => 'confirmed',
                'notes' => 'Medium rare steak please',
                'items' => [
                    ['menu_item_id' => $menuItems->where('name', 'Beef Tenderloin')->first()->id, 'quantity' => 1],
                    ['menu_item_id' => $menuItems->where('name', 'Caesar Salad')->first()->id, 'quantity' => 1],
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
                if ($menuItem) {
                    $subtotal += $menuItem->price * $item['quantity'];
                }
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
                if ($menuItem) {
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

        $this->command->info('Sample orders created successfully!');
    }
}