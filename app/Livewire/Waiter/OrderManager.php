<?php

namespace App\Livewire\Waiter;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class OrderManager extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $orderTypeFilter = '';
    public $restaurantFilter = '';
    public $refreshInterval = 10;
    public int $lastKnownOrderId = 0;
    public bool $newOrderAlert = false;
    public string $alertOrderNumber = '';
    public string $alertCustomer = '';
    public string $alertRestaurant = '';
    public string $alertTotal = '';
    public string $alertType = '';

    // Kitchen ready notification
    public bool $kitchenReadyAlert = false;
    public string $kitchenAlertOrderNumber = '';
    public string $kitchenAlertRestaurant = '';
    public string $kitchenAlertItems = '';
    public string $lastKitchenCheck = '';

    public function mount()
    {
        $this->authorize('view table orders');

        $restaurantId = auth()->user()?->restaurant_id;
        $latestOrder = Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->latest('id')
            ->first();
        $this->lastKnownOrderId = $latestOrder ? $latestOrder->id : 0;
        $this->lastKitchenCheck = now()->toISOString();
    }

    #[On('order-updated')]
    public function refreshOrders()
    {
        $this->resetPage();
    }

    public function checkNewOrders()
    {
        $restaurantId = auth()->user()?->restaurant_id;

        $newOrders = Order::where('id', '>', $this->lastKnownOrderId)
            ->with('restaurant')
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->latest('id')
            ->limit(5)
            ->get();

        if ($newOrders->isNotEmpty()) {
            $latest = $newOrders->first();
            $this->lastKnownOrderId = $latest->id;
            $this->newOrderAlert = true;
            $this->alertOrderNumber = $latest->order_number;
            $this->alertCustomer = $latest->customer_name ?? 'Guest';
            $this->alertRestaurant = $latest->restaurant->name ?? 'Unknown';
            $this->alertTotal = number_format($latest->total_amount, 0);
            $this->alertType = str_replace('_', ' ', $latest->order_type);

            $this->dispatch('new-order-received',
                orderNumber: $this->alertOrderNumber,
                customer: $this->alertCustomer,
                restaurant: $this->alertRestaurant,
                total: $this->alertTotal,
                type: $this->alertType
            );
        }
    }

    public function dismissAlert()
    {
        $this->newOrderAlert = false;
    }

    public function checkKitchenReady()
    {
        $restaurantId = auth()->user()?->restaurant_id;

        $readyItems = OrderItem::where('status', 'ready')
            ->where('updated_at', '>', $this->lastKitchenCheck)
            ->with(['order', 'menuItem.restaurant'])
            ->when($restaurantId, fn($q) => $q->whereHas('menuItem', fn($q2) => $q2->where('restaurant_id', $restaurantId)))
            ->latest('updated_at')
            ->limit(30)
            ->get();

        if ($readyItems->isNotEmpty()) {
            $this->lastKitchenCheck = now()->toISOString();

            // Group by order to get the most relevant info
            $byOrder = $readyItems->groupBy('order_id');
            $firstOrderItems = $byOrder->first();
            $order = $firstOrderItems->first()->order;
            $restaurants = $firstOrderItems
                ->map(fn($i) => $i->menuItem?->restaurant?->name)
                ->filter()
                ->unique()
                ->implode(', ');
            $itemNames = $firstOrderItems
                ->map(fn($i) => $i->item_name)
                ->take(3)
                ->implode(', ');
            if ($firstOrderItems->count() > 3) {
                $itemNames .= ' +' . ($firstOrderItems->count() - 3) . ' more';
            }

            $this->kitchenReadyAlert = true;
            $this->kitchenAlertOrderNumber = $order->order_number ?? '';
            $this->kitchenAlertRestaurant = $restaurants;
            $this->kitchenAlertItems = $itemNames;

            $this->dispatch('kitchen-ready-received',
                orderNumber: $this->kitchenAlertOrderNumber,
                restaurant: $this->kitchenAlertRestaurant,
                items: $this->kitchenAlertItems,
            );
        }
    }

    public function dismissKitchenAlert()
    {
        $this->kitchenReadyAlert = false;
    }

    public function render()
    {
        $user = auth()->user();

        $query = Order::with(['orderItems.menuItem.restaurant', 'restaurant']);

        if ($this->restaurantFilter) {
            $query->where('restaurant_id', $this->restaurantFilter);
        } elseif ($user && $user->restaurant_id && !$this->restaurantFilter) {
            $query->where('restaurant_id', $user->restaurant_id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->orderTypeFilter) {
            $query->where('order_type', $this->orderTypeFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $restaurants = \App\Models\Restaurant::where('is_active', true)->get();

        // Count of pending orders for badge
        $pendingCount = Order::where('status', 'pending')->count();

        // Status-wise counts for tabs (respecting restaurant + order type filters)
        $countQuery = Order::query();

        if ($this->restaurantFilter) {
            $countQuery->where('restaurant_id', $this->restaurantFilter);
        } elseif ($user && $user->restaurant_id && !$this->restaurantFilter) {
            $countQuery->where('restaurant_id', $user->restaurant_id);
        }

        if ($this->orderTypeFilter) {
            $countQuery->where('order_type', $this->orderTypeFilter);
        }

        $statusCounts = $countQuery
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $allCount = $statusCounts->sum();

        return view('livewire.waiter.order-manager', [
            'orders' => $orders,
            'restaurants' => $restaurants,
            'pendingCount' => $pendingCount,
            'statusOptions' => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'preparing' => 'Preparing',
                'ready' => 'Ready',
                'served' => 'Served',
                'completed' => 'Completed',
            ],
            'orderTypeOptions' => [
                'dine_in' => 'Dine In',
                'takeaway' => 'Takeaway',
                'delivery' => 'Delivery',
            ],
            'statusCounts' => $statusCounts,
            'allCount' => $allCount,
        ]);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $this->authorize('update order status');
        
        // Waiter can now update orders from any restaurant
        $order = Order::findOrFail($orderId);
        
        $order->update(['status' => $status]);
        
        if ($status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        // Dispatch event to update kitchen and other components
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: $status);
        
        session()->flash('message', 'Order status updated successfully!');
    }

    public function confirmOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->payment_status !== 'paid') {
            session()->flash('error', 'Please mark payment as paid before confirming the order.');
            return;
        }

        // record which waiter confirmed the order so kitchen can notify the same user later
        $order->update(['assigned_waiter_id' => auth()->id()]);

        $this->updateOrderStatus($orderId, 'confirmed');
    }

    public function markAsServed($orderId)
    {
        $this->updateOrderStatus($orderId, 'served');
    }

    public function serveRestaurantItems($orderId, $restaurantId)
    {
        $this->authorize('update order status');

        $restaurantId = (int) $restaurantId;
        $order = Order::with('orderItems.menuItem')->findOrFail($orderId);

        // Mark only items from this restaurant as served
        foreach ($order->orderItems as $item) {
            if ($item->menuItem && (int) $item->menuItem->restaurant_id == $restaurantId) {
                $item->update(['status' => 'served']);
            }
        }

        // If all items across all restaurants are served, mark the whole order as served
        $allServed = $order->fresh()->orderItems()
            ->where(function ($q) {
                $q->whereNotIn('status', ['served', 'completed'])
                  ->orWhereNull('status');
            })->count() === 0;
        
        if ($allServed) {
            $order->update(['status' => 'served']);
        }

        $this->dispatch('order-updated');
        session()->flash('message', 'Restaurant items marked as served!');
    }

    public function completeOrder($orderId)
    {
        $this->updateOrderStatus($orderId, 'completed');
    }

    public function resetFilters()
    {
        $this->statusFilter = '';
        $this->orderTypeFilter = '';
        $this->restaurantFilter = '';
        $this->resetPage();
    }

    public function refreshData()
    {
        $this->resetPage();
        session()->flash('message', 'Orders refreshed!');
    }

    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $this->authorize('update order status');
        
        $order = Order::findOrFail($orderId);
        
        $order->update(['payment_status' => $paymentStatus]);
        
        // Dispatch event to update other components
        $this->dispatch('order-updated');
        $this->dispatch('payment-status-changed', orderId: $orderId, paymentStatus: $paymentStatus);
        
        session()->flash('message', 'Payment status updated successfully!');
    }
}