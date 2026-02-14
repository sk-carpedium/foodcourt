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
});
