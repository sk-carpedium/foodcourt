<?php

namespace App\Livewire\Components;

use App\Models\Order;
use Livewire\Component;

class SidebarNavigation extends Component
{
    public function render()
    {
        $pendingOrders = 0;
        $kitchenOrders = 0;
        $totalOrders = 0;
        
        if (auth()->user()->restaurant_id) {
            $pendingOrders = Order::where('status', 'pending')
                ->where('restaurant_id', auth()->user()->restaurant_id)
                ->count();
                
            $kitchenOrders = Order::whereIn('status', ['confirmed', 'preparing'])
                ->where('restaurant_id', auth()->user()->restaurant_id)
                ->count();
                
            $totalOrders = Order::where('restaurant_id', auth()->user()->restaurant_id)
                ->whereDate('created_at', today())
                ->count();
        } else {
            // For admin/super-admin, show global stats
            $pendingOrders = Order::where('status', 'pending')->count();
            $kitchenOrders = Order::whereIn('status', ['confirmed', 'preparing'])->count();
            $totalOrders = Order::whereDate('created_at', today())->count();
        }

        return view('livewire.components.sidebar-navigation', [
            'pendingOrders' => $pendingOrders,
            'kitchenOrders' => $kitchenOrders,
            'totalOrders' => $totalOrders,
        ]);
    }

    public function getListeners()
    {
        return [
            'order-updated' => '$refresh',
            'order-created' => '$refresh',
        ];
    }
}