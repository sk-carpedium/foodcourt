<div class="p-6">
    @if(isset($error))
        <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
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

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kitchen Order Queue</h1>
        <button wire:click="refreshData" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition-colors">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh Queue
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Queue -->
        <div class="lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Active Orders</h2>
            <div class="space-y-4">
                @forelse ($orders as $order)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 
                        {{ $order->status === 'confirmed' ? 'border-yellow-400' : 'border-orange-400' }} 
                        p-4 cursor-pointer hover:shadow-lg transition-all duration-200"
                         wire:click="selectOrder({{ $order->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $order->customer_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('H:i') }} 
                                    ({{ $order->created_at->diffForHumans() }})</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    {{ $order->status === 'confirmed' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' }}">
                                    {{ $order->status === 'confirmed' ? '🔔 New Order' : '🔥 Preparing' }}
                                </span>
                                @if($order->estimated_ready_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        🕒 Ready: {{ $order->estimated_ready_at->format('H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-3">
                            <div class="text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Items:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $order->orderItems->count() }} items</span>
                                @if($order->table_number)
                                    <span class="ml-3 font-medium text-gray-700 dark:text-gray-300">Table:</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $order->table_number }}</span>
                                @endif
                                <span class="ml-3 font-medium text-gray-700 dark:text-gray-300">Payment:</span>
                                <span class="text-gray-600 dark:text-gray-400">
                                    @if($order->payment_method === 'cash') 💵 Cash
                                    @elseif($order->payment_method === 'card') 💳 Card
                                    @elseif($order->payment_method === 'bank_transfer') 🏦 Bank
                                    @endif
                                </span>
                            </div>
                            <span class="text-sm capitalize px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-300">
                                {{ str_replace('_', ' ', $order->order_type) }}
                            </span>
                        </div>

                        @if($order->notes)
                            <div class="mb-3 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded text-sm">
                                <span class="font-medium text-yellow-800 dark:text-yellow-200">⚠️ Special Notes:</span>
                                <span class="text-yellow-700 dark:text-yellow-300">{{ $order->notes }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Click to view details
                            </div>
                            <div class="space-x-2">
                                @if($order->status === 'confirmed')
                                    <button wire:click.stop="startPreparing({{ $order->id }})" 
                                        class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600 transition-colors">
                                        🔥 Start Preparing
                                    </button>
                                @endif
                                
                                @if($order->status === 'preparing')
                                    <button wire:click.stop="markReady({{ $order->id }})" 
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                                        ✅ Mark Ready
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                        <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">👨‍🍳</div>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">No orders in queue</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">New orders will appear here when confirmed by waiters</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Order Details -->
        <div class="lg:col-span-1">
            @if($selectedOrder)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-4 sticky top-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Order Details</h3>
                        <button wire:click="closeOrderDetails" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Order:</span>
                            <p class="font-semibold text-gray-900 dark:text-white">#{{ $selectedOrder->order_number }}</p>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer:</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedOrder->customer_name }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Type:</span>
                            <p class="capitalize text-gray-900 dark:text-white">{{ str_replace('_', ' ', $selectedOrder->order_type) }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment:</span>
                            <p class="text-gray-900 dark:text-white">
                                @if($selectedOrder->payment_method === 'cash') 💵 Cash
                                @elseif($selectedOrder->payment_method === 'card') 💳 Card
                                @elseif($selectedOrder->payment_method === 'bank_transfer') 🏦 Bank Transfer
                                @endif
                                <span class="text-xs px-1 py-0.5 rounded ml-1
                                    @if($selectedOrder->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($selectedOrder->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($selectedOrder->payment_status) }}
                                </span>
                            </p>
                        </div>

                        @if($selectedOrder->table_number)
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Table:</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->table_number }}</p>
                            </div>
                        @endif

                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Time:</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedOrder->created_at->format('H:i') }}</p>
                        </div>

                        @if($selectedOrder->notes)
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Special Notes:</span>
                                <p class="text-sm bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded text-yellow-800 dark:text-yellow-200">{{ $selectedOrder->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="font-medium mb-3 text-gray-900 dark:text-white">Items to Prepare:</h4>
                        <div class="space-y-3">
                            @foreach($selectedOrder->orderItems as $item)
                                <div class="border rounded-lg p-3 
                                    @if($item->status === 'pending') border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20
                                    @elseif($item->status === 'preparing') border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20
                                    @else border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20
                                    @endif">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $item->quantity }}x {{ $item->item_name }}</span>
                                            @if($item->special_instructions)
                                                <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-medium">⚠️ {{ $item->special_instructions }}</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-medium ml-2
                                            @if($item->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($item->status === 'preparing') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @endif">
                                            @if($item->status === 'pending') 📋 Pending
                                            @elseif($item->status === 'preparing') 🔥 Preparing
                                            @else ✅ Ready
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex space-x-2 mt-2">
                                        @if($item->status === 'pending')
                                            <button wire:click="updateItemStatus({{ $item->id }}, 'preparing')" 
                                                class="bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600 transition-colors">
                                                🔥 Start
                                            </button>
                                        @endif
                                        
                                        @if($item->status === 'preparing')
                                            <button wire:click="updateItemStatus({{ $item->id }}, 'ready')" 
                                                class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                                ✅ Ready
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500 text-4xl mb-4">👆</div>
                    <p class="text-gray-500 dark:text-gray-400">Select an order to view details</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Click on any order from the queue</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            setInterval(() => {
                @this.call('refreshQueue');
            }, {{ $refreshInterval * 1000 }});
        });
    </script>
</div>