<?php

namespace App\Livewire\Customer;

use App\Models\Restaurant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Livewire\Component;

class Checkout extends Component
{
    public $restaurant;
    public $cart = [];
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_email = '';
    public $order_type = 'dine_in';
    public $payment_method = 'cash';
    public $table_number = '';
    public $delivery_address = '';
    public $notes = '';

    protected $rules = [
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'nullable|string|max:20',
        'customer_email' => 'nullable|email|max:255',
        'order_type' => 'required|in:dine_in,takeaway,delivery',
        'payment_method' => 'required|in:cash,card,bank_transfer',
        'table_number' => 'required_if:order_type,dine_in|nullable|string|max:10',
        'delivery_address' => 'required_if:order_type,delivery|nullable|string',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount($restaurantSlug)
    {
        $this->restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $this->cart = session()->get('cart', []);
        
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty!');
            return redirect()->route('menu', $this->restaurant->slug);
        }

        // Pre-fill customer info if logged in
        if (auth()->check()) {
            $this->customer_name = auth()->user()->name;
            $this->customer_email = auth()->user()->email;
            $this->customer_phone = auth()->user()->phone;
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout', [
            'subtotal' => $this->getSubtotal(),
            'taxAmount' => $this->getTaxAmount(),
            'deliveryFee' => $this->getDeliveryFee(),
            'total' => $this->getTotal(),
        ]);
    }

    public function placeOrder()
    {
        $this->validate();

        $subtotal = $this->getSubtotal();
        $taxAmount = $this->getTaxAmount();
        $deliveryFee = $this->getDeliveryFee();
        $total = $this->getTotal();

        // Create order
        $order = Order::create([
            'restaurant_id' => $this->restaurant->id,
            'user_id' => auth()->id(),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'order_type' => $this->order_type,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_method === 'cash' ? 'pending' : 'pending',
            'table_number' => $this->table_number,
            'delivery_address' => $this->delivery_address,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $total,
            'notes' => $this->notes,
            'estimated_ready_at' => now()->addMinutes($this->getEstimatedTime()),
        ]);

        // Create order items
        foreach ($this->cart as $cartItem) {
            $menuItem = MenuItem::find($cartItem['id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'item_name' => $menuItem->name,
                'item_price' => $menuItem->price,
                'quantity' => $cartItem['quantity'],
                'total_price' => $menuItem->price * $cartItem['quantity'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        $this->dispatch('order-created');
        session()->flash('success', 'Order placed successfully! Order number: ' . $order->order_number);
        return redirect()->route('order.confirmation', $order->order_number);
    }

    private function getSubtotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    private function getTaxAmount()
    {
        return $this->getSubtotal() * 0.1; // 10% tax
    }

    private function getDeliveryFee()
    {
        return $this->order_type === 'delivery' ? 5.00 : 0;
    }

    private function getTotal()
    {
        return $this->getSubtotal() + $this->getTaxAmount() + $this->getDeliveryFee();
    }

    private function getEstimatedTime()
    {
        $totalTime = 0;
        foreach ($this->cart as $cartItem) {
            $menuItem = MenuItem::find($cartItem['id']);
            if ($menuItem) {
                $totalTime += $menuItem->preparation_time * $cartItem['quantity'];
            }
        }
        return max($totalTime, 15); // Minimum 15 minutes
    }
}