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
     * Helper: collect restaurant IDs related to an order (main + item restaurants)
     */
    protected function getRestaurantIdsForOrder(Order $order)
    {
        $ids = collect();

        if ($order->restaurant_id) {
            $ids->push($order->restaurant_id);
        }

        if ($order->relationLoaded('orderItems')) {
            $order->orderItems->each(function ($item) use ($ids) {
                if ($item->menuItem && $item->menuItem->restaurant_id) {
                    $ids->push($item->menuItem->restaurant_id);
                }
            });
        } else {
            // eager load just in case
            $order->load('orderItems.menuItem');
            $order->orderItems->each(function ($item) use ($ids) {
                if ($item->menuItem && $item->menuItem->restaurant_id) {
                    $ids->push($item->menuItem->restaurant_id);
                }
            });
        }

        return $ids->unique()->values();
    }

    /**
     * Get waiter tokens for a given order. If the order has an assigned_waiter_id
     * we only return that user's token; otherwise we grab all waiters for the restaurant(s).
     */
    protected function getWaiterTokens(Order $order)
    {
        $baseQuery = User::role('waiter')->whereNotNull('fcm_token');
        $query = clone $baseQuery;

        if ($order->assigned_waiter_id) {
            $query->where('id', $order->assigned_waiter_id);

            $tokens = $query->get()->unique('fcm_token')->pluck('fcm_token')->filter()->values();
            if ($tokens->isNotEmpty()) {
                return $tokens->all();
            }

            // fallback to restaurant-based tokens if the assigned waiter has no token
            $query = clone $baseQuery;
        }

        $ids = $this->getRestaurantIdsForOrder($order);
        if ($ids->isNotEmpty()) {
            $query->whereIn('restaurant_id', $ids);
        }

        $tokens = $query->get()->unique('fcm_token')->pluck('fcm_token')->filter()->values()->all();

        // Fallback: if no restaurant-scoped waiter is configured, notify all waiters with tokens.
        if (empty($tokens)) {
            $tokens = $baseQuery->get()->unique('fcm_token')->pluck('fcm_token')->filter()->values()->all();
        }

        return $tokens;
    }

    /**
     * Get kitchen staff tokens for the restaurants involved in an order
     */
    protected function getKitchenTokens(Order $order)
    {
        $ids = $this->getRestaurantIdsForOrder($order);

        $baseQuery = User::role('kitchen')
            ->whereNotNull('fcm_token');

        $tokens = (clone $baseQuery)
            ->when($ids->isNotEmpty(), fn($q) => $q->whereIn('restaurant_id', $ids))
            ->get()
            ->unique('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->values()
            ->all();

        // Fallback for setups where kitchen users are not restaurant-mapped yet.
        if (empty($tokens)) {
            $tokens = $baseQuery
                ->get()
                ->unique('fcm_token')
                ->pluck('fcm_token')
                ->filter()
                ->values()
                ->all();
        }

        return $tokens;
    }

    /**
     * Notify waiter(s) about a new order. Tokens are filtered by restaurant or
     * the assigned waiter when available.
     */
    public function notifyWaiterNewOrder(Order $order)
    {
        \Log::info('🔔 NotifyWaiterNewOrder called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'restaurant_ids' => $this->getRestaurantIdsForOrder($order)->all(),
            'table_number' => $order->table_number,
        ]);

        $tokens = $this->getWaiterTokens($order);

        if (empty($tokens)) {
            \Log::warning('⚠️ No waiter tokens available for order', ['order_id' => $order->id]);
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
            'restaurant_ids' => $this->getRestaurantIdsForOrder($order)->implode(','),
            'user_role' => 'waiter',
            'link' => url('/waiter/orders'),
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        \Log::info('📤 Sending waiter notification', compact('notification','data','tokens'));
        $this->fcm->sendToDevices($tokens, $notification, $data);
        \Log::info('✅ NotifyWaiterNewOrder completed');
    }

    /**
     * Notify customer about payment confirmation
     */
    public function notifyCustomerPaymentReceived(Order $order)
    {
        // Disabled: FCM pushes are restricted to waiter role only.
        return;
    }

    /**
     * Notify waiter when order is ready (used by kitchen flow)
     */
    public function notifyWaiterOrderReady(Order $order)
    {
        $tokens = $this->getWaiterTokens($order);
        if (empty($tokens)) {
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
            'restaurant_ids' => $this->getRestaurantIdsForOrder($order)->implode(','),
            'user_role' => 'waiter',
            'link' => url('/waiter/orders'),
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $this->fcm->sendToDevices($tokens, $notification, $data);
    }

    /**
     * Notify waiter when kitchen begins preparing an order
     */
    public function notifyWaiterOrderPreparing(Order $order)
    {
        $tokens = $this->getWaiterTokens($order);
        if (empty($tokens)) {
            return;
        }

        $notification = [
            'title' => '👩‍🍳 Order Being Prepared',
            'body' => "Order #{$order->order_number} is now being prepared",
            'sound' => 'default',
            'badge' => '1',
        ];

        $data = [
            'type' => 'order_preparing',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'restaurant_ids' => $this->getRestaurantIdsForOrder($order)->implode(','),
            'user_role' => 'waiter',
            'link' => url('/waiter/orders'),
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $this->fcm->sendToDevices($tokens, $notification, $data);
    }

    /**
     * Notify kitchen staff when a waiter confirms an order.
     */
    public function notifyKitchenNewOrder(Order $order)
    {
        \Log::info('🍳 notifyKitchenNewOrder called', ['order_id' => $order->id]);

        $tokens = $this->getKitchenTokens($order);
        if (empty($tokens)) {
            \Log::warning('⚠️ No kitchen tokens available for order', ['order_id' => $order->id]);
            return;
        }

        $notification = [
            'title' => '🍽️ New Order Ready for Kitchen',
            'body' => "Order #{$order->order_number} confirmed", 
            'sound' => 'default',
            'badge' => '1',
        ];

        $data = [
            'type' => 'kitchen_new_order',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'table_number' => $order->table_number ?? '',
            'restaurant_ids' => $this->getRestaurantIdsForOrder($order)->implode(','),
            'user_role' => 'kitchen',
            'link' => url('/kitchen/orders'),
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $this->fcm->sendToDevices($tokens, $notification, $data);
    }

    /**
     * Notify customer when order is served
     */
    public function notifyCustomerOrderServed(Order $order)
    {
        // Disabled: FCM pushes are restricted to waiter role only.
        return;
    }
}
