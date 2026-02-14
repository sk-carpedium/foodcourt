<?php

namespace App\Livewire\Components;

use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;

class SidebarStats extends Component
{
    #[On('order-updated')]
    #[On('order-status-changed')]
    public function refreshStats()
    {
        // This will trigger a re-render with fresh data
    }

    public function render()
    {
        $user = auth()->user();
        $stats = [];

        if ($user && $user->hasRole('waiter')) {
            // Independent waiter sees global stats
            $stats = [
                'restaurants' => Restaurant::count(),
                'menu_items' => MenuItem::count(),
                'todays_orders' => Order::whereDate('created_at', today())->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'preparing_orders' => Order::where('status', 'preparing')->count(),
                'ready_orders' => Order::where('status', 'ready')->count(),
                'user_restaurant' => 'Independent Waiter',
            ];
        } elseif ($user && $user->restaurant_id) {
            // Restaurant-specific stats for other staff
            $restaurantId = $user->restaurant_id;
            
            $stats = [
                'restaurants' => 1,
                'menu_items' => MenuItem::where('restaurant_id', $restaurantId)->count(),
                'todays_orders' => Order::where('restaurant_id', $restaurantId)
                    ->whereDate('created_at', today())->count(),
                'pending_orders' => Order::where('restaurant_id', $restaurantId)
                    ->where('status', 'pending')->count(),
                'preparing_orders' => Order::where('restaurant_id', $restaurantId)
                    ->where('status', 'preparing')->count(),
                'ready_orders' => Order::where('restaurant_id', $restaurantId)
                    ->where('status', 'ready')->count(),
                'user_restaurant' => $user->restaurant->name ?? null,
            ];
        } else {
            // Global stats for super-admin
            $stats = [
                'restaurants' => Restaurant::count(),
                'menu_items' => MenuItem::count(),
                'todays_orders' => Order::whereDate('created_at', today())->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'preparing_orders' => Order::where('status', 'preparing')->count(),
                'ready_orders' => Order::where('status', 'ready')->count(),
                'user_restaurant' => null,
            ];
        }

        return view('livewire.components.sidebar-stats', compact('stats'));
    }

    public function placeholder()
    {
        return view('livewire.components.sidebar-stats-placeholder');
    }
}