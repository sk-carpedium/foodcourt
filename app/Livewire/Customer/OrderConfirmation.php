<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public $order;

    public function mount($orderNumber)
    {
        $this->order = Order::with(['orderItems.menuItem', 'restaurant'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.customer.order-confirmation');
    }
}