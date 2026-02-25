<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\WebPushService;
use Livewire\Component;
use Livewire\Attributes\On;

class OrderQueue extends Component
{
    public $selectedOrder = null;
    public $refreshInterval = 15; // seconds

    public $activeTab = 'active';

    // New order notification
    public bool $newOrderAlert = false;
    public string $alertOrderNumber = '';
    public string $alertCustomer = '';
    public string $alertTable = '';
    public string $alertItems = '';
    public string $alertType = '';
    public string $lastNewOrderCheck = '';

    public function mount()
    {
        $this->authorize('view kitchen orders');
        $this->lastNewOrderCheck = now()->toISOString();
    }

    #[On('order-updated')]
    #[On('order-status-changed')]
    public function refreshQueue()
    {
        if ($this->selectedOrder) {
            $this->selectedOrder = Order::with(['orderItems.menuItem.restaurant'])
                ->find($this->selectedOrder->id);
        }
    }

    public function checkNewConfirmedOrders()
    {
        $restaurantId = (int) auth()->user()->restaurant_id;

        $newOrders = Order::with(['orderItems.menuItem', 'restaurant'])
            ->where('payment_status', 'paid')
            ->where('status', 'confirmed')
            ->where('updated_at', '>', $this->lastNewOrderCheck)
            ->where(function ($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId)
                  ->orWhereHas('orderItems.menuItem', function ($q2) use ($restaurantId) {
                      $q2->where('restaurant_id', $restaurantId);
                  });
            })
            ->get();

        if ($newOrders->isNotEmpty()) {
            $this->lastNewOrderCheck = now()->toISOString();
            $order = $newOrders->first();

            $myItems = $order->orderItems
                ->filter(fn($i) => $i->menuItem && (int) $i->menuItem->restaurant_id == $restaurantId)
                ->map(fn($i) => $i->quantity . 'x ' . $i->item_name)
                ->take(4)
                ->implode(', ');

            $this->newOrderAlert = true;
            $this->alertOrderNumber = $order->order_number ?? '';
            $this->alertCustomer = $order->customer_name ?? 'Guest';
            $this->alertTable = $order->table_number ?? '-';
            $this->alertItems = $myItems;
            $this->alertType = ucfirst(str_replace('_', ' ', $order->order_type));

            $this->dispatch('new-kitchen-order',
                orderNumber: $this->alertOrderNumber,
                customer: $this->alertCustomer,
                items: $this->alertItems,
            );
        }
    }

    public function dismissAlert()
    {
        $this->newOrderAlert = false;
    }

    public function render()
    {
        $user = auth()->user();
        $restaurantId = $user ? $user->restaurant_id : null;
        
        if (!$restaurantId) {
            return view('livewire.kitchen.order-queue', [
                'activeOrders' => collect(),
                'completedOrders' => collect(),
                'error' => 'You are not assigned to any restaurant. Please contact your administrator.'
            ]);
        }

        $activeOrders = Order::with(['orderItems.menuItem.restaurant', 'restaurant'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['confirmed', 'preparing', 'ready'])
            ->where(function ($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId)
                  ->orWhereHas('orderItems.menuItem', function ($q2) use ($restaurantId) {
                      $q2->where('restaurant_id', $restaurantId);
                  });
            })
            ->orderBy('created_at')
            ->get();

        $completedOrders = Order::with(['orderItems.menuItem.restaurant', 'restaurant'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['served', 'completed'])
            ->where(function ($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId)
                  ->orWhereHas('orderItems.menuItem', function ($q2) use ($restaurantId) {
                      $q2->where('restaurant_id', $restaurantId);
                  });
            })
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get();

        return view('livewire.kitchen.order-queue', [
            'activeOrders' => $activeOrders,
            'completedOrders' => $completedOrders,
        ]);
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with(['orderItems.menuItem.restaurant'])
            ->findOrFail($orderId);
    }

    public function startPreparing($orderId)
    {
        $this->authorize('update kitchen status');
        $restaurantId = (int) auth()->user()->restaurant_id;
        
        $order = Order::with('orderItems.menuItem')->findOrFail($orderId);
        
        // Only update this kitchen's items
        foreach ($order->orderItems as $item) {
            if ($item->menuItem && (int) $item->menuItem->restaurant_id == $restaurantId) {
                $item->update(['status' => 'preparing']);
            }
        }

        // Set order to preparing if not already
        if ($order->status === 'confirmed') {
            $order->update(['status' => 'preparing']);
        }
        
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: 'preparing');
        
        session()->flash('message', 'Your items marked as preparing!');
    }

    public function markReady($orderId)
    {
        $this->authorize('update kitchen status');
        $restaurantId = (int) auth()->user()->restaurant_id;
        
        $order = Order::with('orderItems.menuItem')->findOrFail($orderId);
        
        // Only update this kitchen's items
        foreach ($order->orderItems as $item) {
            if ($item->menuItem && (int) $item->menuItem->restaurant_id == $restaurantId) {
                $item->update(['status' => 'ready']);
            }
        }

        // Check if ALL items across all restaurants are ready (handle NULL status too)
        $notReady = $order->fresh()->orderItems()
            ->where(function ($q) {
                $q->where('status', '!=', 'ready')
                  ->orWhereNull('status');
            })->count();
        
        if ($notReady === 0) {
            $order->update(['status' => 'ready']);
        }

        // Notify waiters when this kitchen has finished all of its items.
        if ($this->allKitchenItemsReadyForOrder($order->id, $restaurantId)) {
            app(WebPushService::class)->notifyWaitersKitchenReady(
                $order->fresh()->load('orderItems.menuItem.restaurant'),
                $restaurantId
            );
        }
        
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: 'ready');
        
        session()->flash('message', 'Your items marked as ready!');
    }

    public function updateItemStatus($orderItemId, $status)
    {
        $this->authorize('update kitchen status');
        
        $orderItem = OrderItem::findOrFail($orderItemId);
        $orderItem->update(['status' => $status]);
        
        // Check if all items are ready, then mark order as ready
        $order = $orderItem->order->fresh();
        $notReady = $order->orderItems()
            ->where(function ($q) {
                $q->where('status', '!=', 'ready')
                  ->orWhereNull('status');
            })->count();
        
        if ($notReady === 0 && in_array($order->status, ['confirmed', 'preparing'])) {
            $order->update(['status' => 'ready']);
            $this->dispatch('order-status-changed', orderId: $order->id, status: 'ready');
        } elseif ($order->status === 'confirmed') {
            $order->update(['status' => 'preparing']);
        }

        if ($status === 'ready') {
            $restaurantId = (int) auth()->user()->restaurant_id;
            if ($this->allKitchenItemsReadyForOrder($order->id, $restaurantId)) {
                app(WebPushService::class)->notifyWaitersKitchenReady(
                    $order->fresh()->load('orderItems.menuItem.restaurant'),
                    $restaurantId
                );
            }
        }
        
        $this->dispatch('order-updated');
        session()->flash('message', 'Item status updated!');
        
        $this->refreshQueue();
    }

    public function closeOrderDetails()
    {
        $this->selectedOrder = null;
    }

    public function refreshData()
    {
        $this->refreshQueue();
        session()->flash('message', 'Kitchen queue refreshed!');
    }

    private function allKitchenItemsReadyForOrder(int $orderId, int $restaurantId): bool
    {
        $notReadyCount = OrderItem::where('order_id', $orderId)
            ->whereHas('menuItem', function ($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            })
            ->where(function ($q) {
                $q->where('status', '!=', 'ready')
                  ->orWhereNull('status');
            })
            ->count();

        return $notReadyCount === 0;
    }
}