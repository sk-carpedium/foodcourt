<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function isConfigured(): bool
    {
        return (bool) config('webpush.vapid.public_key')
            && (bool) config('webpush.vapid.private_key')
            && (bool) config('webpush.vapid.subject');
    }

    public function notifyKitchensForConfirmedOrder(Order $order): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $restaurantIds = $order->orderItems
            ->map(fn ($item) => $item->menuItem?->restaurant_id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($restaurantIds) && $order->restaurant_id) {
            $restaurantIds = [(int) $order->restaurant_id];
        }

        if (empty($restaurantIds)) {
            return;
        }

        $kitchenUserIds = User::role('kitchen')
            ->whereIn('restaurant_id', $restaurantIds)
            ->pluck('id');

        if ($kitchenUserIds->isEmpty()) {
            return;
        }

        $subscriptions = PushSubscription::whereIn('user_id', $kitchenUserIds)
            ->where('channel', 'kitchen')
            ->get();

        $payload = [
            'title' => 'New Kitchen Order',
            'body' => "#{$order->order_number} - {$order->customer_name}",
            'url' => route('kitchen.orders'),
            'tag' => 'kitchen-order-' . $order->id,
        ];

        $this->sendToSubscriptions($subscriptions, $payload);
    }

    public function notifyWaitersKitchenReady(Order $order, int $restaurantId): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $restaurantName = optional($order->orderItems
            ->first(fn ($item) => (int) ($item->menuItem?->restaurant_id ?? 0) === $restaurantId)?->menuItem?->restaurant)->name
            ?? 'Kitchen';

        $waiterUserIds = User::role(['waiter', 'admin', 'super-admin'])->pluck('id');

        if ($waiterUserIds->isEmpty()) {
            return;
        }

        $subscriptions = PushSubscription::whereIn('user_id', $waiterUserIds)
            ->where('channel', 'waiter')
            ->get();

        $payload = [
            'title' => 'Kitchen Ready',
            'body' => "#{$order->order_number} - {$restaurantName}",
            'url' => route('waiter.orders'),
            'tag' => 'kitchen-ready-' . $order->id . '-' . $restaurantId,
        ];

        $this->sendToSubscriptions($subscriptions, $payload);
    }

    private function sendToSubscriptions($subscriptions, array $payload): void
    {
        if ($subscriptions->isEmpty()) {
            return;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
                'contentEncoding' => $sub->content_encoding ?: 'aes128gcm',
            ]);

            $webPush->queueNotification($subscription, json_encode($payload));
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                continue;
            }

            $expiredEndpoint = $report->getEndpoint();
            if ($expiredEndpoint) {
                PushSubscription::where('endpoint', $expiredEndpoint)->delete();
            }
        }
    }
}
