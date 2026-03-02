<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class OrderNotificationService
{
    protected $fcm;

    public function __construct(FcmService $fcm)
    {
        $this->fcm = $fcm;
    }

    /**
     * Notify waiter about new order
     */
    public function notifyWaiterNewOrder(Order $order)
    {
        \Log::info('🔔 NotifyWaiterNewOrder called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'restaurant_id' => $order->restaurant_id,
            'table_number' => $order->table_number,
        ]);

        // Waiters are global in this project (not restaurant-restricted).
        $waiters = User::role('waiter')
            ->whereNotNull('fcm_token')
            ->get();

        \Log::info('👥 Waiters found', [
            'count' => $waiters->count(),
            'waiters' => $waiters->map(fn($w) => [
                'id' => $w->id,
                'name' => $w->name,
                'restaurant_id' => $w->restaurant_id,
                'has_token' => !empty($w->fcm_token),
                'token_preview' => substr($w->fcm_token, 0, 20) . '...'
            ])
        ]);

        if ($waiters->isEmpty()) {
            \Log::warning('⚠️ No waiters with FCM tokens found');
            return;
        }

        $notification = [
            'title' => '🔔 New Order Received',
            'body' => "Table {$order->table_number} - Order #{$order->order_number}",
            'sound' => 'default',
            'badge' => '1',
        ];

        $data = [
            'type' => 'new_order',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'table_number' => $order->table_number ?? '',
            'restaurant_id' => (string) $order->restaurant_id,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        \Log::info('📤 Sending notifications', [
            'notification' => $notification,
            'data' => $data,
        ]);

        foreach ($waiters as $waiter) {
            \Log::info('📨 Sending to waiter', [
                'waiter_id' => $waiter->id,
                'waiter_name' => $waiter->name,
            ]);
            
            $result = $this->fcm->sendToDevice($waiter->fcm_token, $notification, $data);
            
            \Log::info('📬 Notification result', [
                'waiter_id' => $waiter->id,
                'success' => $result['success'],
                'failure' => $result['failure'],
            ]);
        }
        
        \Log::info('✅ NotifyWaiterNewOrder completed');
    }

    /**
     * Notify customer about payment confirmation
     */
    public function notifyCustomerPaymentReceived(Order $order)
    {
        // If customer has FCM token (for mobile app)
        if ($order->user && $order->user->fcm_token) {
            $notification = [
                'title' => '✅ Payment Confirmed',
                'body' => "Your order #{$order->order_number} is confirmed and being prepared",
                'sound' => 'default',
            ];

            $data = [
                'type' => 'payment_confirmed',
                'order_id' => (string) $order->id,
                'order_number' => $order->order_number,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ];

            $this->fcm->sendToDevice($order->user->fcm_token, $notification, $data);
        }

        // Also send email/SMS if available
        // TODO: Implement email/SMS notification
    }

    /**
     * Notify waiter when order is ready
     */
    public function notifyWaiterOrderReady(Order $order)
    {
        // Waiters are global in this project (not restaurant-restricted).
        $waiters = User::role('waiter')
            ->whereNotNull('fcm_token')
            ->get();

        if ($waiters->isEmpty()) {
            return;
        }

        $notification = [
            'title' => '🍽️ Order Ready for Pickup',
            'body' => "Order #{$order->order_number} ready - Table {$order->table_number}",
            'sound' => 'default',
            'badge' => '1',
        ];

        $data = [
            'type' => 'order_ready',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'table_number' => $order->table_number ?? '',
            'restaurant_id' => (string) $order->restaurant_id,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        foreach ($waiters as $waiter) {
            $this->fcm->sendToDevice($waiter->fcm_token, $notification, $data);
        }
    }

    /**
     * Notify kitchen about new confirmed order
     */
    public function notifyKitchenNewOrder(Order $order)
    {
        $kitchenStaff = User::role('kitchen')
            ->where('restaurant_id', $order->restaurant_id)
            ->whereNotNull('fcm_token')
            ->get();

        if ($kitchenStaff->isEmpty()) {
            return;
        }

        $itemCount = $order->orderItems->count();
        
        $notification = [
            'title' => '👨‍🍳 New Order to Prepare',
            'body' => "Order #{$order->order_number} - {$itemCount} items - Table {$order->table_number}",
            'sound' => 'default',
            'badge' => '1',
        ];

        $data = [
            'type' => 'kitchen_order',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'table_number' => $order->table_number ?? '',
            'item_count' => (string) $itemCount,
            'restaurant_id' => (string) $order->restaurant_id,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        foreach ($kitchenStaff as $staff) {
            $this->fcm->sendToDevice($staff->fcm_token, $notification, $data);
        }
    }

    /**
     * Notify customer when order is served
     */
    public function notifyCustomerOrderServed(Order $order)
    {
        if ($order->user && $order->user->fcm_token) {
            $notification = [
                'title' => '🎉 Enjoy Your Meal!',
                'body' => "Your order has been served. Bon appétit!",
                'sound' => 'default',
            ];

            $data = [
                'type' => 'order_served',
                'order_id' => (string) $order->id,
                'order_number' => $order->order_number,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ];

            $this->fcm->sendToDevice($order->user->fcm_token, $notification, $data);
        }
    }
}
