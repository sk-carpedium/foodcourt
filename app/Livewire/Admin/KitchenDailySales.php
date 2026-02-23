<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Livewire\Component;

class KitchenDailySales extends Component
{
    public string $selectedDate = '';

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
    }

    public function updatedSelectedDate()
    {
        // triggers re-render
    }

    public function previousDay()
    {
        $this->selectedDate = \Carbon\Carbon::parse($this->selectedDate)->subDay()->toDateString();
    }

    public function nextDay()
    {
        $this->selectedDate = \Carbon\Carbon::parse($this->selectedDate)->addDay()->toDateString();
    }

    public function render()
    {
        $date = $this->selectedDate ?: now()->toDateString();

        $restaurants = Restaurant::where('is_active', true)
            ->with(['users' => fn($q) => $q->role('kitchen')])
            ->get();

        $kitchenSales = [];
        $grandTotalRevenue = 0;
        $grandTotalOrders = 0;
        $grandTotalItems = 0;

        foreach ($restaurants as $restaurant) {
            // Get order items belonging to this restaurant sold on the selected date
            $items = OrderItem::whereHas('order', function ($q) use ($date) {
                    $q->where('payment_status', 'paid')
                      ->whereDate('created_at', $date);
                })
                ->whereHas('menuItem', function ($q) use ($restaurant) {
                    $q->where('restaurant_id', $restaurant->id);
                })
                ->with(['menuItem', 'order'])
                ->get();

            if ($items->isEmpty()) {
                $kitchenSales[] = [
                    'restaurant' => $restaurant,
                    'totalRevenue' => 0,
                    'totalItems' => 0,
                    'orderCount' => 0,
                    'itemBreakdown' => collect(),
                    'paymentBreakdown' => ['cash' => 0, 'card' => 0],
                ];
                continue;
            }

            $totalRevenue = $items->sum('total_price');
            $totalItemsQty = $items->sum('quantity');
            $orderIds = $items->pluck('order_id')->unique();
            $orderCount = $orderIds->count();

            // Item-wise breakdown
            $itemBreakdown = $items->groupBy('menu_item_id')->map(function ($group) {
                return [
                    'name' => $group->first()->item_name,
                    'qty' => $group->sum('quantity'),
                    'revenue' => $group->sum('total_price'),
                ];
            })->sortByDesc('revenue')->values();

            // Payment method breakdown
            $orders = Order::whereIn('id', $orderIds)->get();
            $cashTotal = $orders->where('payment_method', 'cash')->sum(function ($o) use ($items) {
                return $items->where('order_id', $o->id)->sum('total_price');
            });
            $cardTotal = $orders->where('payment_method', 'card')->sum(function ($o) use ($items) {
                return $items->where('order_id', $o->id)->sum('total_price');
            });

            $grandTotalRevenue += $totalRevenue;
            $grandTotalOrders += $orderCount;
            $grandTotalItems += $totalItemsQty;

            $kitchenSales[] = [
                'restaurant' => $restaurant,
                'totalRevenue' => $totalRevenue,
                'totalItems' => $totalItemsQty,
                'orderCount' => $orderCount,
                'itemBreakdown' => $itemBreakdown,
                'paymentBreakdown' => ['cash' => $cashTotal, 'card' => $cardTotal],
            ];
        }

        // Sort: restaurants with sales first
        usort($kitchenSales, fn($a, $b) => $b['totalRevenue'] <=> $a['totalRevenue']);

        return view('livewire.admin.kitchen-daily-sales', [
            'kitchenSales' => $kitchenSales,
            'grandTotalRevenue' => $grandTotalRevenue,
            'grandTotalOrders' => $grandTotalOrders,
            'grandTotalItems' => $grandTotalItems,
        ]);
    }
}
