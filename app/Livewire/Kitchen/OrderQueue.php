<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Livewire\Attributes\On;

class OrderQueue extends Component
{
    public $selectedOrder = null;
    public $refreshInterval = 15; // seconds

    public function mount()
    {
        $this->authorize('view kitchen orders');
    }

    #[On('order-updated')]
    #[On('order-status-changed')]
    public function refreshQueue()
    {
        // Refresh the selected order if it's still selected
        if ($this->selectedOrder) {
            $this->selectedOrder = Order::with(['orderItems.menuItem'])
                ->where('restaurant_id', auth()->user()->restaurant_id)
                ->find($this->selectedOrder->id);
        }
    }

    public function render()
    {
        $user = auth()->user();
        $restaurantId = $user ? $user->restaurant_id : null;
        
        if (!$restaurantId) {
            return view('livewire.kitchen.order-queue', [
                'orders' => collect(),
                'error' => 'You are not assigned to any restaurant. Please contact your administrator.'
            ]);
        }

        $orders = Order::with(['orderItems.menuItem', 'restaurant'])
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['confirmed', 'preparing'])
            ->orderBy('created_at')
            ->get();

        return view('livewire.kitchen.order-queue', [
            'orders' => $orders
        ]);
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with(['orderItems.menuItem'])
            ->where('restaurant_id', auth()->user()->restaurant_id)
            ->findOrFail($orderId);
    }

    public function startPreparing($orderId)
    {
        $this->authorize('update kitchen status');
        
        $order = Order::where('restaurant_id', auth()->user()->restaurant_id)
            ->findOrFail($orderId);
        
        $order->update(['status' => 'preparing']);
        
        // Update all order items to preparing status
        $order->orderItems()->update(['status' => 'preparing']);
        
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: 'preparing');
        
        session()->flash('message', 'Order marked as preparing!');
    }

    public function markReady($orderId)
    {
        $this->authorize('update kitchen status');
        
        $order = Order::where('restaurant_id', auth()->user()->restaurant_id)
            ->findOrFail($orderId);
        
        $order->update(['status' => 'ready']);
        
        // Mark all order items as ready
        $order->orderItems()->update(['status' => 'ready']);
        
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: 'ready');
        
        session()->flash('message', 'Order marked as ready!');
    }

    public function updateItemStatus($orderItemId, $status)
    {
        $this->authorize('update kitchen status');
        
        $orderItem = OrderItem::whereHas('order', function($query) {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        })->findOrFail($orderItemId);
        
        $orderItem->update(['status' => $status]);
        
        // Check if all items are ready, then mark order as ready
        $order = $orderItem->order;
        $allItemsReady = $order->orderItems()->where('status', '!=', 'ready')->count() === 0;
        
        if ($allItemsReady && $order->status === 'preparing') {
            $order->update(['status' => 'ready']);
            $this->dispatch('order-status-changed', orderId: $order->id, status: 'ready');
        }
        
        $this->dispatch('order-updated');
        session()->flash('message', 'Item status updated!');
        
        // Refresh selected order to show updated status
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
}