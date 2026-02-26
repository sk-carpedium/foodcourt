<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function vapidPublicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key'),
            'enabled' => (bool) config('webpush.vapid.public_key'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'channel' => 'required|in:waiter,kitchen',
            'subscription.endpoint' => 'required|string',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $validated['subscription']['endpoint']],
            [
                'user_id' => $request->user()->id,
                'channel' => $validated['channel'],
                'public_key' => $validated['subscription']['keys']['p256dh'],
                'auth_token' => $validated['subscription']['keys']['auth'],
                'content_encoding' => 'aes128gcm',
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        PushSubscription::where('endpoint', $validated['endpoint'])
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['ok' => true]);
    }
}
