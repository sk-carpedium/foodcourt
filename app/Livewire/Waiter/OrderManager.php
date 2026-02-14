<?php

namespace App\Livewire\Waiter;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class OrderManager extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $orderTypeFilter = '';
    public $restaurantFilter = '';
    public $refreshInterval = 30; // seconds

    public function mount()
    {
        $this->authorize('view table orders');
    }

    #[On('order-updated')]
    public function refreshOrders()
    {
        // Refresh the component when orders are updated
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        
        // Waiter can now see orders from all restaurants or select a specific one
        $query = Order::with(['orderItems.menuItem', 'restaurant']);
        
        // Apply restaurant filter
        if ($this->restaurantFilter) {
            $query->where('restaurant_id', $this->restaurantFilter);
        } elseif ($user && $user->restaurant_id && !$this->restaurantFilter) {
            // Default to user's restaurant if they have one and no filter is set
            $query->where('restaurant_id', $user->restaurant_id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->orderTypeFilter) {
            $query->where('order_type', $this->orderTypeFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get all restaurants for the filter dropdown
        $restaurants = \App\Models\Restaurant::where('is_active', true)->get();

        return view('livewire.waiter.order-manager', [
            'orders' => $orders,
            'restaurants' => $restaurants,
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
            ]
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
        $this->updateOrderStatus($orderId, 'confirmed');
    }

    public function markAsServed($orderId)
    {
        $this->updateOrderStatus($orderId, 'served');
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