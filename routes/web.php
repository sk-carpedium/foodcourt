<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Admin\RestaurantManager;
use App\Livewire\Admin\MenuCategoryManager;
use App\Livewire\Admin\MenuItemManager;
use App\Livewire\Customer\RestaurantMenu;
use App\Livewire\Customer\Checkout;
use App\Livewire\Waiter\OrderManager;
use App\Livewire\Kitchen\OrderQueue;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Runtime Firebase config for page + service worker
Route::get('/firebase-config-runtime.js', function () {
    $payload = [
        'apiKey' => env('FIREBASE_API_KEY', ''),
        'authDomain' => env('FIREBASE_AUTH_DOMAIN', ''),
        'projectId' => env('FIREBASE_PROJECT_ID', ''),
        'storageBucket' => env('FIREBASE_STORAGE_BUCKET', ''),
        'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
        'appId' => env('FIREBASE_APP_ID', ''),
        'measurementId' => env('FIREBASE_MEASUREMENT_ID', ''),
    ];

    $js = 'self.FIREBASE_CONFIG = ' . json_encode($payload, JSON_UNESCAPED_SLASHES) . ';';

    return response($js, 200)
        ->header('Content-Type', 'application/javascript')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
})->name('firebase.config.runtime');

// FCM Token Registration (for web push notifications)
Route::post('/api/fcm/register', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
        ], 401);
    }

    $request->validate([
        'fcm_token' => 'required|string',
    ]);

    auth()->user()->update([
        'fcm_token' => $request->fcm_token,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'FCM token registered successfully',
        'user' => auth()->user()->name,
    ]);
})->name('fcm.register');

Route::post('/api/fcm/unregister', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
        ], 401);
    }

    auth()->user()->update([
        'fcm_token' => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'FCM token removed successfully',
    ]);
})->name('fcm.unregister');

// Customer Routes (Public)
Route::get('/restaurant/{restaurantSlug}/menu', [App\Http\Controllers\RestaurantController::class, 'menu'])->name('menu');
Route::get('/restaurant/{restaurantSlug}/checkout', [App\Http\Controllers\RestaurantController::class, 'checkout'])->name('checkout');
Route::get('/order/{orderNumber}/confirmation', function($orderNumber) {
    $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
    return view('pages.order-confirmation', compact('order'));
})->name('order.confirmation');
Route::get('/track-order', function() {
    return view('pages.order-tracking');
})->name('order.track');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('users', \App\Livewire\UserManagement::class)->name('users.index');

    // Admin Routes
    Route::middleware(['role:admin|super-admin'])->prefix('admin')->group(function () {
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('restaurant-menu-manager', \App\Livewire\Admin\RestaurantMenuManager::class)->name('admin.restaurant-menu-manager');
        Route::get('orders', \App\Livewire\Admin\OrderManager::class)->name('admin.orders');
        Route::get('invoices', \App\Livewire\Admin\InvoiceManager::class)->name('admin.invoices');
        Route::get('kitchen-sales', \App\Livewire\Admin\KitchenDailySales::class)->name('admin.kitchen-sales');
        Route::get('invoice/{orderId}/print', function($orderId) {
            $order = \App\Models\Order::with(['orderItems.menuItem', 'restaurant'])->findOrFail($orderId);
            return view('pages.invoice-print', compact('order'));
        })->name('admin.invoice.print');
        
        // Legacy routes for backward compatibility
        Route::get('menu-manager', \App\Livewire\Admin\MenuManager::class)->name('admin.menu-manager');
        Route::get('restaurants', RestaurantManager::class)->name('admin.restaurants');
        Route::get('menu-categories', MenuCategoryManager::class)->name('admin.menu-categories');
        Route::get('menu-items', MenuItemManager::class)->name('admin.menu-items');
    });

    // Waiter Routes
    Route::middleware(['role:waiter|admin|super-admin'])->prefix('waiter')->group(function () {
        Route::get('orders', OrderManager::class)->name('waiter.orders');
    });

    // Kitchen Routes
    Route::middleware(['role:kitchen|admin|super-admin'])->prefix('kitchen')->group(function () {
        Route::get('orders', OrderQueue::class)->name('kitchen.orders');
    });

    // Cashier Routes
    Route::middleware(['role:cashier|admin|super-admin'])->prefix('cashier')->group(function () {
        Route::get('invoices', \App\Livewire\Cashier\InvoiceViewer::class)->name('cashier.invoices');
    });
});
