<div class="min-h-screen bg-gradient-to-br from-pink-50 via-red-50 to-orange-50 p-4 sm:p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-6 mb-6 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-4 right-4 w-20 h-20 bg-gradient-to-br from-pink-400 to-red-400 rounded-full"></div>
            <div class="absolute bottom-4 left-4 w-16 h-16 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full"></div>
        </div>
        
        <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-pink-600 via-red-500 to-orange-500 bg-clip-text text-transparent">
                    🍽️ Order Management
                </h1>
                <p class="text-slate-600 mt-1 font-medium">Admin Portal - Complete Order Control</p>
            </div>
            <button wire:click="refreshData" class="bg-gradient-to-r from-pink-500 via-red-500 to-orange-500 text-white px-8 py-3 rounded-xl hover:from-pink-600 hover:via-red-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl font-semibold w-full sm:w-auto transform hover:scale-105">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Orders
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-6 mb-6 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-2 left-2 w-12 h-12 bg-gradient-to-br from-red-400 to-pink-400 rounded-full"></div>
            <div class="absolute bottom-2 right-2 w-8 h-8 bg-gradient-to-br from-orange-400 to-red-400 rounded-full"></div>
        </div>
        
        <h2 class="relative text-lg font-bold text-gray-900 mb-4 flex items-center">
            <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-red-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            🔍 Advanced Filters
        </h2>
        <!-- Mobile: Single column layout -->
        <div class="relative space-y-4 md:hidden">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                    🏪 Restaurant
                </label>
                <select wire:model.live="restaurantFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 px-4 py-2.5 hover:border-pink-300 transition-all">
                    <option value="">All Restaurants</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                    📊 Order Status
                </label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                    🍽️ Order Type
                </label>
                <select wire:model.live="orderTypeFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Types</option>
                    @foreach($orderTypeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                    💳 Payment Status
                </label>
                <select wire:model.live="paymentStatusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Payment Status</option>
                    @foreach($paymentStatusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                    💰 Payment Method
                </label>
                <select wire:model.live="paymentMethodFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Methods</option>
                    @foreach($paymentMethodOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="resetFilters" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                🔄 Reset Filters
            </button>
        </div>

        <!-- Tablet: 2 column layout -->
        <div class="relative hidden md:grid lg:hidden grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🏪 Restaurant</label>
                <select wire:model.live="restaurantFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 px-4 py-2.5 hover:border-pink-300 transition-all">
                    <option value="">All Restaurants</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📊 Order Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🍽️ Order Type</label>
                <select wire:model.live="orderTypeFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Types</option>
                    @foreach($orderTypeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💳 Payment Status</label>
                <select wire:model.live="paymentStatusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Payment Status</option>
                    @foreach($paymentStatusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💰 Payment Method</label>
                <select wire:model.live="paymentMethodFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Methods</option>
                    @foreach($paymentMethodOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="resetFilters" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                🔄 Reset Filters
            </button>
        </div>

        <!-- Desktop: 3 column layout -->
        <div class="relative hidden lg:grid xl:hidden grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🏪 Restaurant</label>
                <select wire:model.live="restaurantFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 px-4 py-2.5 hover:border-pink-300 transition-all">
                    <option value="">All Restaurants</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📊 Order Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🍽️ Order Type</label>
                <select wire:model.live="orderTypeFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Types</option>
                    @foreach($orderTypeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💳 Payment Status</label>
                <select wire:model.live="paymentStatusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Payment Status</option>
                    @foreach($paymentStatusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💰 Payment Method</label>
                <select wire:model.live="paymentMethodFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Methods</option>
                    @foreach($paymentMethodOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="resetFilters" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                🔄 Reset Filters
            </button>
        </div>

        <!-- Large Desktop: 6 column layout -->
        <div class="relative hidden xl:grid grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🏪 Restaurant</label>
                <select wire:model.live="restaurantFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 px-4 py-2.5 hover:border-pink-300 transition-all">
                    <option value="">All Restaurants</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📊 Order Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🍽️ Order Type</label>
                <select wire:model.live="orderTypeFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Types</option>
                    @foreach($orderTypeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💳 Payment Status</label>
                <select wire:model.live="paymentStatusFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Payment Status</option>
                    @foreach($paymentStatusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">💰 Payment Method</label>
                <select wire:model.live="paymentMethodFilter" class="w-full rounded-xl border-2 border-pink-200 bg-white shadow-sm focus:border-pink-400 focus:ring-2 focus:ring-pink-200 text-sm font-semibold text-gray-900 hover:border-pink-300 transition-all px-4 py-2.5">
                    <option value="">All Methods</option>
                    @foreach($paymentMethodOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button wire:click="resetFilters" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                    🔄 Reset
                </button>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border-2 border-green-200 text-green-800 px-6 py-4 rounded-2xl mb-6 shadow-lg">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="font-semibold">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Mobile-Responsive Orders Display -->
    <div class="space-y-6">
        @forelse ($orders as $order)
            <!-- Mobile Card Layout -->
            <div class="lg:hidden bg-white rounded-2xl shadow-lg border border-pink-100 p-6 relative overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-2 right-2 w-16 h-16 bg-gradient-to-br from-pink-400 to-red-400 rounded-full"></div>
                    <div class="absolute bottom-2 left-2 w-12 h-12 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full"></div>
                </div>
                
                <div class="relative flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">
                            #{{ $order->order_number }}
                        </h3>
                        <p class="text-sm text-gray-600 font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            PKR {{ number_format($order->total_amount, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 font-medium">{{ $order->orderItems->count() }} items</p>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700 flex items-center">
                            👤 Customer:
                        </span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700 flex items-center">
                            🏪 Restaurant:
                        </span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $order->restaurant->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700 flex items-center">
                            🍽️ Type:
                        </span>
                        <span class="text-sm text-gray-900 font-semibold capitalize">{{ str_replace('_', ' ', $order->order_type) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700 flex items-center">
                            💳 Payment:
                        </span>
                        <div class="text-right">
                            <div class="text-sm text-gray-900 font-semibold">
                                @if($order->payment_method === 'cash') 💵 Cash
                                @elseif($order->payment_method === 'card') 💳 Card
                                @elseif($order->payment_method === 'bank_transfer') 🏦 Bank Transfer
                                @endif
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                @if($order->payment_status === 'paid') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800
                                @elseif($order->payment_status === 'failed') bg-gradient-to-r from-red-100 to-pink-100 text-red-800
                                @else bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <span class="px-4 py-2 text-sm font-bold rounded-full
                        @if($order->status === 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800
                        @elseif($order->status === 'preparing') bg-gradient-to-r from-orange-100 to-red-100 text-orange-800
                        @elseif($order->status === 'ready') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800
                        @elseif($order->status === 'served') bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800
                        @elseif($order->status === 'completed') bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800
                        @else bg-gradient-to-r from-red-100 to-pink-100 text-red-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                    @if($order->table_number)
                        <span class="text-sm text-gray-600 font-semibold bg-gradient-to-r from-blue-50 to-indigo-50 px-3 py-1 rounded-full">
                            🪑 Table: {{ $order->table_number }}
                        </span>
                    @endif
                </div>

                <!-- Mobile Action Buttons -->
                <div class="space-y-3">
                    <!-- Order Status Actions -->
                    @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <div class="flex flex-wrap gap-2">
                            @if($order->status === 'pending')
                                <button wire:click="updateOrderStatus({{ $order->id }}, 'confirmed')" 
                                    class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-2 rounded-xl text-sm hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                    ✅ Confirm
                                </button>
                            @endif
                            
                            @if(in_array($order->status, ['confirmed', 'preparing']))
                                <button wire:click="updateOrderStatus({{ $order->id }}, 'ready')" 
                                    class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded-xl text-sm hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                    🍽️ Ready
                                </button>
                            @endif
                            
                            @if($order->status === 'ready')
                                <button wire:click="updateOrderStatus({{ $order->id }}, 'served')" 
                                    class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-xl text-sm hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                    🚚 Served
                                </button>
                            @endif
                            
                            @if($order->status === 'served')
                                <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" 
                                    class="bg-gradient-to-r from-gray-500 to-slate-500 text-white px-4 py-2 rounded-xl text-sm hover:from-gray-600 hover:to-slate-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                    ✨ Complete
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Payment Status Actions -->
                    <div class="flex flex-wrap gap-2">
                        @if($order->payment_status === 'pending')
                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'paid')" 
                                class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded-xl text-sm hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                💰 Mark Paid
                            </button>
                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'failed')" 
                                class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-xl text-sm hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                ❌ Failed
                            </button>
                        @endif
                        
                        @if($order->payment_status === 'paid')
                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'refunded')" 
                                class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-xl text-sm hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                🔄 Refund
                            </button>
                        @endif
                        
                        @if($order->payment_status === 'failed')
                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'pending')" 
                                class="bg-gradient-to-r from-yellow-500 to-amber-500 text-white px-4 py-2 rounded-xl text-sm hover:from-yellow-600 hover:to-amber-600 transition-all duration-200 shadow-lg font-semibold flex-1 min-w-0 transform hover:scale-105">
                                🔄 Retry
                            </button>
                        @endif
                    </div>

                    <!-- Cancel Order -->
                    @if(!in_array($order->status, ['cancelled', 'completed']))
                        <button wire:click="cancelOrder({{ $order->id }})" 
                            onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-xl text-sm hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                            🚫 Cancel Order
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="lg:hidden bg-white rounded-2xl shadow-lg border border-pink-100 p-12 text-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-4 right-4 w-20 h-20 bg-gradient-to-br from-pink-400 to-red-400 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full"></div>
                </div>
                
                <div class="relative">
                    <div class="text-8xl mb-6">📋</div>
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent mb-4">
                        No Orders Found
                    </h3>
                    <p class="text-gray-600 text-lg font-medium mb-6">Orders will appear here when customers place them</p>
                    <button wire:click="refreshData" class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-8 py-3 rounded-xl hover:from-pink-600 hover:to-red-600 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                        🔄 Refresh Orders
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table Layout -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-lg border border-pink-100 overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-4 right-4 w-16 h-16 bg-gradient-to-br from-pink-400 to-red-400 rounded-full"></div>
            <div class="absolute bottom-4 left-4 w-12 h-12 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full"></div>
        </div>
        
        <div class="relative overflow-x-auto">
            <table class="min-w-full divide-y divide-pink-200">
                <thead class="bg-gradient-to-r from-pink-50 via-red-50 to-orange-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">📋 Order</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">👤 Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">🏪 Restaurant</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">🍽️ Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">💳 Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">📊 Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">💰 Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">⚡ Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-pink-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-red-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">
                                    #{{ $order->order_number }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">{{ $order->created_at->format('M d, Y H:i') }}</div>
                                @if($order->table_number)
                                    <div class="text-xs text-gray-500 font-medium bg-blue-50 px-2 py-1 rounded-full inline-block mt-1">
                                        🪑 Table: {{ $order->table_number }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</div>
                                @if($order->customer_phone)
                                    <div class="text-sm text-gray-600 font-medium">📞 {{ $order->customer_phone }}</div>
                                @endif
                                @if($order->customer_email)
                                    <div class="text-sm text-gray-600 font-medium">📧 {{ $order->customer_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $order->restaurant->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">
                                    @if($order->payment_method === 'cash') 💵 Cash
                                    @elseif($order->payment_method === 'card') 💳 Card
                                    @elseif($order->payment_method === 'bank_transfer') 🏦 Bank Transfer
                                    @endif
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    @if($order->payment_status === 'paid') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800
                                    @elseif($order->payment_status === 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800
                                    @elseif($order->payment_status === 'failed') bg-gradient-to-r from-red-100 to-pink-100 text-red-800
                                    @else bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    @if($order->status === 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800
                                    @elseif($order->status === 'preparing') bg-gradient-to-r from-orange-100 to-red-100 text-orange-800
                                    @elseif($order->status === 'ready') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800
                                    @elseif($order->status === 'served') bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800
                                    @elseif($order->status === 'completed') bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800
                                    @else bg-gradient-to-r from-red-100 to-pink-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                    PKR {{ number_format($order->total_amount, 2) }}
                                </div>
                                <div class="text-xs text-gray-500 font-medium">{{ $order->orderItems->count() }} items</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 px-4 py-2.5">
                                <div class="flex flex-col space-y-2">
                                    <!-- Order Status Actions -->
                                    @if($order->status !== 'cancelled' && $order->status !== 'completed')
                                        <div class="flex space-x-1">
                                            @if($order->status === 'pending')
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'confirmed')" 
                                                    class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-3 py-1 rounded-lg text-xs hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                    ✅ Confirm
                                                </button>
                                            @endif
                                            
                                            @if(in_array($order->status, ['confirmed', 'preparing']))
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'ready')" 
                                                    class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-lg text-xs hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                    🍽️ Ready
                                                </button>
                                            @endif
                                            
                                            @if($order->status === 'ready')
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'served')" 
                                                    class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-lg text-xs hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                    🚚 Served
                                                </button>
                                            @endif
                                            
                                            @if($order->status === 'served')
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" 
                                                    class="bg-gradient-to-r from-gray-500 to-slate-500 text-white px-3 py-1 rounded-lg text-xs hover:from-gray-600 hover:to-slate-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                    ✨ Complete
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Payment Status Actions -->
                                    <div class="flex space-x-1">
                                        @if($order->payment_status === 'pending')
                                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'paid')" 
                                                class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-lg text-xs hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                💰 Paid
                                            </button>
                                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'failed')" 
                                                class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-lg text-xs hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                ❌ Failed
                                            </button>
                                        @endif
                                        
                                        @if($order->payment_status === 'paid')
                                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'refunded')" 
                                                class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-lg text-xs hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                🔄 Refund
                                            </button>
                                        @endif
                                        
                                        @if($order->payment_status === 'failed')
                                            <button wire:click="updatePaymentStatus({{ $order->id }}, 'pending')" 
                                                class="bg-gradient-to-r from-yellow-500 to-amber-500 text-white px-3 py-1 rounded-lg text-xs hover:from-yellow-600 hover:to-amber-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                                🔄 Retry
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Cancel Order -->
                                    @if(!in_array($order->status, ['cancelled', 'completed']))
                                        <button wire:click="cancelOrder({{ $order->id }})" 
                                            onclick="return confirm('Are you sure you want to cancel this order?')"
                                            class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-lg text-xs hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-md font-semibold transform hover:scale-105">
                                            🚫 Cancel
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="text-8xl mb-6">📋</div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent mb-4">
                                    No Orders Found
                                </h3>
                                <p class="text-gray-600 text-lg font-medium mb-6">Orders will appear here when customers place them</p>
                                <button wire:click="refreshData" class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-8 py-3 rounded-xl hover:from-pink-600 hover:to-red-600 transition-all duration-200 shadow-lg font-semibold transform hover:scale-105">
                                    🔄 Refresh Orders
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            setInterval(() => {
                @this.call('refreshOrders');
            }, {{ $refreshInterval * 1000 }});
        });
    </script>
</div>