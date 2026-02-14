<x-layouts.guest :title="'Order Confirmation - ' . $order->order_number">
    <livewire:customer.order-confirmation :order-number="$order->order_number" />
</x-layouts.guest>