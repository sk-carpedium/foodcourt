<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-4 sm:p-6">
    @if(isset($error))
        <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center shadow-sm">
            <div class="text-red-400 text-6xl mb-4">⚠️</div>
            <h2 class="text-xl font-semibold text-red-800 mb-2">Access Restricted</h2>
            <p class="text-red-600 mb-4">{{ $error }}</p>
            <div class="bg-white rounded-lg p-4 mt-4">
                <h3 class="font-semibold text-gray-800 mb-2">Available Test Accounts:</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Waiter:</strong> waiter@gourmetkitchen.com / password</p>
                    <p><strong>Kitchen:</strong> kitchen@gourmetkitchen.com / password</p>
                    <p><strong>Admin:</strong> admin@gourmetkitchen.com / password</p>
                </div>
            </div>
        </div>
        @return
    @endif

    @if(!isset($error))
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Order Management
                    </h1>
                    <p class="text-slate-600 mt-1">Independent Waiter Portal</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <button wire:click="resetFilters" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-2.5 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-sm font-medium text-sm">
                        🔄 Reset Filters
                    </button>
                    <button wire:click="refreshData" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm font-medium text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Orders
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter Orders
            </h2>
            
            <!-- Mobile: Single column -->
            <div class="space-y-4 sm:hidden">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🏪 Restaurant</label>
                    <select wire:model.live="restaurantFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Restaurants</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Status</label>
                    <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🍽️ Order Type</label>
                    <select wire:model.live="orderTypeFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Types</option>
                        @foreach($orderTypeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Desktop: Horizontal layout -->
            <div class="hidden sm:grid sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🏪 Restaurant</label>
                    <select wire:model.live="restaurantFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Restaurants</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Status</label>
                    <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🍽️ Order Type</label>
                    <select wire:model.live="orderTypeFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 hover:bg-white transition-colors">
                        <option value="">All Types</option>
                        @foreach($orderTypeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <!-- Orders Grid -->
    <div class="space-y-6">
        @forelse ($orders as $order)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-200 hover:border-blue-200">
                <!-- Order Header -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">Order #{{ $order->order_number }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $order->restaurant->name }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($order->status === 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800
                                    @elseif($order->status === 'preparing') bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800
                                    @elseif($order->status === 'ready') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                    @elseif($order->status === 'served') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800
                                    @elseif($order->status === 'completed') bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800
                                    @else bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $order->customer_name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </span>
                            @if($order->customer_phone)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $order->customer_phone }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-green-600">PKR {{ number_format($order->total_amount, 2) }}</div>
                        <div class="text-sm text-gray-500">{{ $order->orderItems->count() }} items</div>
                    </div>
                </div>

                <!-- Order Details Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-lg">
                    <div class="text-center">
                        <div class="text-2xl mb-1">🍽️</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Type</div>
                        <div class="font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $order->order_type) }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl mb-1">
                            @if($order->payment_method === 'cash') 💵
                            @elseif($order->payment_method === 'card') 💳
                            @elseif($order->payment_method === 'bank_transfer') 🏦
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Payment</div>
                        <div class="font-semibold text-gray-900">
                            @if($order->payment_method === 'cash') Cash
                            @elseif($order->payment_method === 'card') Card
                            @elseif($order->payment_method === 'bank_transfer') Bank Transfer
                            @endif
                        </div>
                        <div class="text-xs px-2 py-1 rounded-full mt-1
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </div>
                    </div>
                    @if($order->table_number)
                        <div class="text-center">
                            <div class="text-2xl mb-1">🪑</div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Table</div>
                            <div class="font-semibold text-gray-900">{{ $order->table_number }}</div>
                        </div>
                    @endif
                    @if($order->estimated_ready_at)
                        <div class="text-center">
                            <div class="text-2xl mb-1">⏰</div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Ready At</div>
                            <div class="font-semibold text-gray-900">{{ $order->estimated_ready_at->format('H:i') }}</div>
                        </div>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <h4 class="font-semibold mb-3 text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Order Items
                    </h4>
                    <div class="space-y-2">
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">{{ $item->quantity }}x {{ $item->item_name }}</span>
                                <span class="font-semibold text-gray-900">PKR {{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($order->notes)
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <h4 class="font-semibold mb-2 text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Special Notes
                        </h4>
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 p-4 rounded-lg">
                            <p class="text-yellow-800 font-medium">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                        <!-- Order Status Actions -->
                        <div class="flex flex-wrap gap-2">
                            @if($order->status === 'pending')
                                <button wire:click="confirmOrder({{ $order->id }})" 
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Confirm Order
                                </button>
                            @endif
                            
                            @if($order->status === 'ready')
                                <button wire:click="markAsServed({{ $order->id }})" 
                                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2.5 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Mark as Served
                                </button>
                            @endif
                            
                            @if($order->status === 'served')
                                <button wire:click="completeOrder({{ $order->id }})" 
                                    class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-2.5 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Complete Order
                                </button>
                            @endif
                        </div>

                        <!-- Payment Status Actions -->
                        <div class="flex flex-wrap gap-2">
                            @if($order->payment_status === 'pending')
                                <button wire:click="updatePaymentStatus({{ $order->id }}, 'paid')" 
                                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-2.5 rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm font-medium text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Mark Paid
                                </button>
                            @endif
                            
                            @if($order->payment_status === 'paid' && in_array($order->status, ['served', 'completed']))
                                <button wire:click="updatePaymentStatus({{ $order->id }}, 'refunded')" 
                                    class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2.5 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-sm font-medium text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refund
                                </button>
                            @endif
                            
                            @if($order->payment_status === 'failed')
                                <button wire:click="updatePaymentStatus({{ $order->id }}, 'pending')" 
                                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-4 py-2.5 rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 shadow-sm font-medium text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Retry Payment
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="text-slate-400 text-8xl mb-6">📋</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Orders Found</h3>
                <p class="text-gray-600 mb-4">
                    @if($restaurantFilter)
                        No orders found for the selected restaurant. Try adjusting your filters.
                    @else
                        Orders from all restaurants will appear here when customers place them.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if($restaurantFilter || $statusFilter || $orderTypeFilter)
                        <button wire:click="resetFilters" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm font-medium">
                            Clear All Filters
                        </button>
                    @endif
                    <button wire:click="refreshData" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-2.5 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-sm font-medium">
                        Refresh Orders
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    @if(!isset($error))
        <div class="mt-8 flex justify-center">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                {{ $orders->links() }}
            </div>
        </div>
    @endif

    <!-- Auto-refresh script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            setInterval(() => {
                @this.call('refreshOrders');
            }, {{ $refreshInterval * 1000 }});
        });
    </script>
</div>