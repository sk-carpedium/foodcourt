<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;

class OrderTracking extends Component
{
    public $orderNumber = '';
    public $order = null;
    public $notFound = false;

    public function trackOrder()
    {
        $this->validate([
            'orderNumber' => 'required|string'
        ]);

        $this->order = Order::with(['orderItems.menuItem', 'restaurant'])
            ->where('order_number', $this->orderNumber)
            ->first();

        if (!$this->order) {
            $this->notFound = true;
        } else {
            $this->notFound = false;
        }
    }

    public function render()
    {
        return view('livewire.customer.order-tracking');
    }
}