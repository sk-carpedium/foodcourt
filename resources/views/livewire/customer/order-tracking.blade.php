<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Track Your Order</h1>
            
            <!-- Order Number Input -->
            <form wire:submit.prevent="trackOrder" class="mb-8">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="orderNumber" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your order number
                        </label>
                        <input 
                            type="text" 
                            id="orderNumber"
                            wire:model="orderNumber" 
                            placeholder="e.g., ORD-ABC123"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('orderNumber') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Track Order
                    </button>
                </div>
            </form>

            <!-- Order Not Found -->
            @if($notFound)
                <div class="text-center py-8">
                    <div class="text-red-400 text-6xl mb-4">❌</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Order Not Found</h3>
                    <p class="text-gray-600">Please check your order number and try again.</p>
                </div>
            @endif

            <!-- Order Details -->
            @if($order)
                <div class="space-y-6">
                    <!-- Order Status -->
                    <div class="text-center">
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'preparing' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'served' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            @php
                                $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'served'];
                                $currentIndex = array_search($order->status, $statuses);
                                if ($currentIndex === false) $currentIndex = 0;
                            @endphp
                            
                            @foreach(['pending', 'confirmed', 'preparing', 'ready', 'served'] as $index => $status)
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                                        {{ $index <= $currentIndex ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-xs mt-2 text-center">{{ ucfirst($status) }}</span>
                                </div>
                                @if($index < 4)
                                    <div class="flex-1 h-1 mx-2 {{ $index < $currentIndex ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Order Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Order Number:</span>
                                <span class="ml-2 font-mono">{{ $order->order_number }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Restaurant:</span>
                                <span class="ml-2">{{ $order->restaurant->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Order Type:</span>
                                <span class="ml-2 capitalize">{{ str_replace('_', ' ', $order->order_type) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Order Time:</span>
                                <span class="ml-2">{{ $order->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($order->estimated_ready_at)
                                <div>
                                    <span class="font-medium text-gray-700">Estimated Ready:</span>
                                    <span class="ml-2 text-green-600 font-semibold">{{ $order->estimated_ready_at->format('H:i') }}</span>
                                </div>
                            @endif
                            @if($order->table_number)
                                <div>
                                    <span class="font-medium text-gray-700">Table:</span>
                                    <span class="ml-2">{{ $order->table_number }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Order Items</h3>
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $item->quantity }}x {{ $item->item_name }}</span>
                                        @if($item->special_instructions)
                                            <div class="text-sm text-gray-600">Note: {{ $item->special_instructions }}</div>
                                        @endif
                                    </div>
                                    <span class="font-semibold">PKR {{ number_format($item->total_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="border-t mt-4 pt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-green-600">PKR {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    @if($order->restaurant->phone)
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-blue-800">
                                Need help? Contact {{ $order->restaurant->name }} at 
                                <a href="tel:{{ $order->restaurant->phone }}" class="font-semibold hover:underline">
                                    {{ $order->restaurant->phone }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>