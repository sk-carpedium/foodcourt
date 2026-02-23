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
            Refresh
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
        <button wire:click="$set('activeTab', 'active')"
            class="px-5 py-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'active' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            🔥 Active Orders
            @if($activeOrders->count())
                <span class="ml-1.5 bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $activeOrders->count() }}</span>
            @endif
        </button>
        <button wire:click="$set('activeTab', 'completed')"
            class="px-5 py-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            ✅ Completed
            @if($completedOrders->count())
                <span class="ml-1.5 bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $completedOrders->count() }}</span>
            @endif
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order List -->
        <div class="lg:col-span-2">
            @if($activeTab === 'active')
                {{-- Active Orders --}}
                <div class="space-y-4">
                    @forelse ($activeOrders as $order)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 
                            @if($order->status === 'confirmed') border-yellow-400
                            @elseif($order->status === 'preparing') border-orange-400
                            @else border-green-400
                            @endif
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
                                        @if($order->status === 'confirmed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($order->status === 'preparing') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @endif">
                                        @if($order->status === 'confirmed') 🔔 New Order
                                        @elseif($order->status === 'preparing') 🔥 Preparing
                                        @else ✅ Ready
                                        @endif
                                    </span>
                                    @if($order->estimated_ready_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            🕒 Ready: {{ $order->estimated_ready_at->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @php $myItems = $order->orderItems->filter(fn($i) => $i->menuItem && (int) $i->menuItem->restaurant_id == (int) auth()->user()->restaurant_id); @endphp
                            <div class="flex justify-between items-center mb-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Items:</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $myItems->count() }}</span>
                                    @if($order->table_number)
                                        <span class="ml-3 font-medium text-gray-700 dark:text-gray-300">Table:</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $order->table_number }}</span>
                                    @endif
                                    <span class="ml-3 font-medium text-gray-700 dark:text-gray-300">Payment:</span>
                                    <span class="text-gray-600 dark:text-gray-400">
                                        @if($order->payment_method === 'cash') 💵 Cash
                                        @elseif($order->payment_method === 'card') 💳 Card
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
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No active orders</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">New orders will appear here when confirmed by waiters</p>
                        </div>
                    @endforelse
                </div>
            @else
                {{-- Completed Orders --}}
                <div class="space-y-4">
                    @forelse ($completedOrders as $order)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-l-4 
                            @if($order->status === 'ready') border-green-400
                            @elseif($order->status === 'served') border-purple-400
                            @else border-gray-400
                            @endif
                            p-4 cursor-pointer hover:shadow-md transition-all duration-200"
                             wire:click="selectOrder({{ $order->id }})">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('H:i') }} — {{ $order->updated_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    @if($order->status === 'ready') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($order->status === 'served') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    @if($order->status === 'ready') ✅ Ready
                                    @elseif($order->status === 'served') 🍽️ Served
                                    @else ✔️ Completed
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $order->orderItems->count() }} items
                                    @if($order->table_number) &middot; Table {{ $order->table_number }} @endif
                                </span>
                                <span>Rs. {{ number_format($order->total_amount, 0) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">📋</div>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No completed orders yet</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>

        <!-- Order Details / Print Sidebar -->
        <div class="lg:col-span-1">
            @if($selectedOrder)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-4 sticky top-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Order Details</h3>
                        <div class="flex items-center gap-2">
                            <button onclick="printSlip()" class="text-gray-500 hover:text-orange-500 transition-colors" title="Print Slip">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </button>
                            <button wire:click="closeOrderDetails" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
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
                        <h4 class="font-medium mb-3 text-gray-900 dark:text-white">Your Items to Prepare:</h4>
                        <div class="space-y-3">
                            @php $myRestaurantId = (int) auth()->user()->restaurant_id; @endphp
                            @foreach($selectedOrder->orderItems->filter(fn($item) => $item->menuItem && (int) $item->menuItem->restaurant_id == $myRestaurantId) as $item)
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
                                    
                                    @if($selectedOrder->status === 'confirmed' || $selectedOrder->status === 'preparing')
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
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Print Slip Button --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                        <button onclick="printSlip()" class="w-full bg-gray-900 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Preparing Slip
                        </button>
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

    {{-- Hidden printable slip --}}
    @if($selectedOrder)
    <div id="print-slip" class="hidden">
        <div style="width: 280px; font-family: 'Courier New', monospace; padding: 10px;">
            <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 8px; margin-bottom: 8px;">
                <div style="font-size: 16px; font-weight: bold;">KITCHEN ORDER SLIP</div>
                <div style="font-size: 11px; margin-top: 4px;">{{ $selectedOrder->restaurant->name ?? '' }}</div>
            </div>

            <div style="font-size: 12px; margin-bottom: 8px;">
                <div><strong>Order:</strong> #{{ $selectedOrder->order_number }}</div>
                <div><strong>Customer:</strong> {{ $selectedOrder->customer_name }}</div>
                <div><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $selectedOrder->order_type)) }}</div>
                @if($selectedOrder->table_number)
                    <div><strong>Table:</strong> {{ $selectedOrder->table_number }}</div>
                @endif
                <div><strong>Time:</strong> {{ $selectedOrder->created_at->format('d M Y, H:i') }}</div>
            </div>

            <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 8px 0; margin-bottom: 8px;">
                <div style="font-weight: bold; font-size: 13px; margin-bottom: 6px;">ITEMS TO PREPARE:</div>
                @foreach($selectedOrder->orderItems->filter(fn($i) => $i->menuItem && (int) $i->menuItem->restaurant_id == (int) auth()->user()->restaurant_id) as $item)
                    <div style="margin-bottom: 6px; font-size: 12px;">
                        <div><strong>{{ $item->quantity }}x {{ $item->item_name }}</strong></div>
                        @if($item->special_instructions)
                            <div style="color: #c00; font-size: 11px;">NOTE: {{ $item->special_instructions }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($selectedOrder->notes)
                <div style="font-size: 11px; margin-bottom: 8px; padding: 4px; border: 1px solid #000;">
                    <strong>ORDER NOTES:</strong> {{ $selectedOrder->notes }}
                </div>
            @endif

            <div style="text-align: center; font-size: 10px; color: #666; border-top: 1px dashed #000; padding-top: 8px;">
                Printed: {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </div>
    @endif

    <!-- New Order Toast -->
    @if($newOrderAlert)
        <div class="fixed top-4 right-4 z-50" wire:key="kitchen-new-order-toast"
             x-data="{ show: true }" x-init="setTimeout(() => { show = false; $wire.dismissAlert(); }, 5000)" x-show="show"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-1rem]" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-yellow-500 text-white rounded-lg shadow-lg px-4 py-3 flex items-center gap-3 cursor-pointer" wire:click="dismissAlert">
                <span class="text-lg">🔔</span>
                <div class="text-sm"><span class="font-semibold">New Order</span> #{{ $alertOrderNumber }} — {{ $alertCustomer }} @if($alertTable !== '-')· Table {{ $alertTable }}@endif</div>
                <svg class="w-4 h-4 opacity-60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
        </div>
    @endif

    @script
    <script>
        setInterval(() => {
            $wire.call('refreshQueue');
            $wire.call('checkNewConfirmedOrders');
        }, {{ $refreshInterval * 1000 }});

        $wire.on('new-kitchen-order', (params) => {
            playKitchenAlertSound();
            showBrowserNotification(params);
        });

        function playKitchenAlertSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const playTone = (freq, startTime, duration, type) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.frequency.value = freq;
                    osc.type = type || 'sine';
                    gain.gain.setValueAtTime(0.35, audioCtx.currentTime + startTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + startTime + duration);
                    osc.start(audioCtx.currentTime + startTime);
                    osc.stop(audioCtx.currentTime + startTime + duration);
                };
                // Urgent double-bell sound
                playTone(880, 0, 0.2, 'sine');
                playTone(880, 0.25, 0.2, 'sine');
                playTone(1175, 0.5, 0.3, 'sine');
                playTone(1175, 0.85, 0.3, 'sine');
            } catch (e) {}
        }

        function showBrowserNotification(data) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('🔔 New Kitchen Order!', {
                    body: '#' + data.orderNumber + '\n' + data.customer + '\nItems: ' + data.items,
                    icon: '/favicon.ico',
                    tag: 'kitchen-new-order',
                });
            }
        }

        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        window.printSlip = function() {
            const slipEl = document.getElementById('print-slip');
            if (!slipEl) return;

            const printWindow = window.open('', '_blank', 'width=320,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Kitchen Order Slip</title>
                    <style>
                        body { margin: 0; padding: 0; }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>${slipEl.innerHTML}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 300);
        };
    </script>
    @endscript
</div>
