<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderNotificationService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->safeNotify(function (OrderNotificationService $service) use ($order) {
            $service->notifyWaiterNewOrder($order);
        }, 'notifyWaiterNewOrder', $order);
    }

    public function updated(Order $order): void
    {
        if (!$order->wasChanged('status')) {
            return;
        }

        $status = $order->status;

        if ($status === 'confirmed') {
            $this->safeNotify(function (OrderNotificationService $service) use ($order) {
                $service->notifyKitchenNewOrder($order);
            }, 'notifyKitchenNewOrder', $order);
            return;
        }

        if ($status === 'preparing') {
            $this->safeNotify(function (OrderNotificationService $service) use ($order) {
                $service->notifyWaiterOrderPreparing($order);
            }, 'notifyWaiterOrderPreparing', $order);
            return;
        }

        if ($status === 'ready') {
            $this->safeNotify(function (OrderNotificationService $service) use ($order) {
                $service->notifyWaiterOrderReady($order);
            }, 'notifyWaiterOrderReady', $order);
        }
    }

    protected function safeNotify(callable $callback, string $action, Order $order): void
    {
        try {
            $callback(app(OrderNotificationService::class));
        } catch (\Throwable $e) {
            Log::error('Order observer notification failed', [
                'action' => $action,
                'order_id' => $order->id,
                'status' => $order->status,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
