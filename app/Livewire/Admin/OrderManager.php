<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Restaurant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class OrderManager extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $orderTypeFilter = '';
    public $restaurantFilter = '';
    public $paymentStatusFilter = '';
    public $paymentMethodFilter = '';
    public $refreshInterval = 30; // seconds

    public function mount()
    {
        $this->authorize('view orders');
    }

    #[On('order-updated')]
    public function refreshOrders()
    {
        // Refresh the component when orders are updated
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with(['orderItems.menuItem', 'restaurant', 'user']);
        
        // Apply filters
        if ($this->restaurantFilter) {
            $query->where('restaurant_id', $this->restaurantFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->orderTypeFilter) {
            $query->where('order_type', $this->orderTypeFilter);
        }

        if ($this->paymentStatusFilter) {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        if ($this->paymentMethodFilter) {
            $query->where('payment_method', $this->paymentMethodFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all restaurants for the filter dropdown
        $restaurants = Restaurant::where('is_active', true)->get();

        return view('livewire.admin.order-manager', [
            'orders' => $orders,
            'restaurants' => $restaurants,
            'statusOptions' => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'preparing' => 'Preparing',
                'ready' => 'Ready',
                'served' => 'Served',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
            'orderTypeOptions' => [
                'dine_in' => 'Dine In',
                'takeaway' => 'Takeaway',
                'delivery' => 'Delivery',
            ],
            'paymentStatusOptions' => [
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ],
            'paymentMethodOptions' => [
                'cash' => 'Cash',
                'card' => 'Card',
                'bank_transfer' => 'Bank Transfer',
            ]
        ]);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $this->authorize('update orders');
        
        $order = Order::findOrFail($orderId);
        
        $order->update(['status' => $status]);
        
        if ($status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        // Dispatch event to update other components
        $this->dispatch('order-updated');
        $this->dispatch('order-status-changed', orderId: $orderId, status: $status);
        
        session()->flash('message', 'Order status updated successfully!');
    }

    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $this->authorize('update orders');
        
        $order = Order::findOrFail($orderId);
        
        $order->update(['payment_status' => $paymentStatus]);
        
        // Dispatch event to update other components
        $this->dispatch('order-updated');
        $this->dispatch('payment-status-changed', orderId: $orderId, paymentStatus: $paymentStatus);
        
        session()->flash('message', 'Payment status updated successfully!');
    }

    public function cancelOrder($orderId)
    {
        $this->authorize('update orders');
        
        $order = Order::findOrFail($orderId);
        
        $order->update([
            'status' => 'cancelled',
            'payment_status' => $order->payment_status === 'paid' ? 'refunded' : $order->payment_status
        ]);
        
        $this->dispatch('order-updated');
        
        session()->flash('message', 'Order cancelled successfully!');
    }

    public function resetFilters()
    {
        $this->statusFilter = '';
        $this->orderTypeFilter = '';
        $this->restaurantFilter = '';
        $this->paymentStatusFilter = '';
        $this->paymentMethodFilter = '';
        $this->resetPage();
    }

    public function refreshData()
    {
        $this->resetPage();
        session()->flash('message', 'Orders refreshed!');
    }
}