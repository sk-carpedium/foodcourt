<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('menu', $restaurant->slug) }}" class="text-gray-700 hover:text-blue-600">{{ $restaurant->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-500">Checkout</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-900">Complete Your Order</h1>
            
            <!-- Pay First Notice -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Pay First - Order Later</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <p>Please complete payment before placing your order. Our waiter will collect payment and then your order will be sent to the kitchen for preparation.</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Form -->
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-900">Delivery Information</h2>
                    
                    <form wire:submit.prevent="placeOrder" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" wire:model="customer_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" wire:model="customer_phone" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" wire:model="customer_email" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Type *</label>
                            <select wire:model.live="order_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="dine_in">🍽️ Dine In</option>
                                <option value="takeaway">🥡 Takeaway</option>
                                <option value="delivery">🚚 Delivery</option>
                            </select>
                            @error('order_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method * (Pay First)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="relative">
                                    <input type="radio" wire:model.live="payment_method" value="cash" class="sr-only peer">
                                    <div class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-colors">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">💵</div>
                                            <div class="font-medium text-gray-900">Cash</div>
                                            <div class="text-xs text-gray-500">Pay to waiter first</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" wire:model.live="payment_method" value="card" class="sr-only peer">
                                    <div class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-colors">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">💳</div>
                                            <div class="font-medium text-gray-900">Card</div>
                                            <div class="text-xs text-gray-500">Pay to waiter first</div>
                                        </div>
                                    </div>
                                </label>

                            </div>
                            @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if($order_type === 'dine_in')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Table Number *</label>
                                <input type="text" wire:model="table_number" placeholder="e.g., Table 5" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('table_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($order_type === 'delivery')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address *</label>
                                <textarea wire:model="delivery_address" rows="3" placeholder="Enter your full delivery address" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('delivery_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                            <textarea wire:model="notes" rows="3" placeholder="Any special requests or dietary requirements..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                            class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                            Confirm Order (Pay First) - PKR {{ number_format($total, 2) }}
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center mt-2">
                            ⚠️ Payment will be collected by waiter before order preparation
                        </p>
                    </form>
                </div>

                <!-- Order Summary -->
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-900">Order Summary</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="space-y-3">
                            @foreach($cart as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                                        <div class="text-sm text-gray-600">PKR {{ number_format($item['price'], 2) }} each</div>
                                    </div>
                                    <span class="font-medium">PKR {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t mt-4 pt-4 space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>PKR {{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Tax ({{ $this->getTaxRate() }}%):</span>
                                <span>PKR {{ number_format($taxAmount, 2) }}</span>
                            </div>
                            
                            @if($deliveryFee > 0)
                                <div class="flex justify-between">
                                    <span>Delivery Fee:</span>
                                    <span>PKR {{ number_format($deliveryFee, 2) }}</span>
                                </div>
                            @endif
                            
                            <div class="border-t pt-2 flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span class="text-green-600">PKR {{ number_format($total, 2) }}</span>
                            </div>

                            @if($payment_method)
                                <div class="flex justify-between text-sm text-gray-600 pt-2">
                                    <span>Payment Method:</span>
                                    <span class="capitalize">
                                        @if($payment_method === 'cash') 💵 Cash
                                        @elseif($payment_method === 'card') 💳 Card
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Restaurant Info -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-blue-900 mb-2">Restaurant Information</h3>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p class="font-semibold">{{ $restaurant->name }}</p>
                            @if($restaurant->phone)
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $restaurant->phone }}
                                </p>
                            @endif
                            @if($restaurant->address)
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $restaurant->address }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Estimated Time -->
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="font-medium text-yellow-900 mb-2">⏱️ Order Process</h3>
                        <div class="text-sm text-yellow-800 space-y-2">
                            <p class="flex items-start">
                                <span class="font-semibold mr-2">1.</span>
                                <span>Waiter will be notified of your order</span>
                            </p>
                            <p class="flex items-start">
                                <span class="font-semibold mr-2">2.</span>
                                <span>Waiter collects payment (Cash/Card)</span>
                            </p>
                            <p class="flex items-start">
                                <span class="font-semibold mr-2">3.</span>
                                <span>Order sent to kitchen for preparation</span>
                            </p>
                            <p class="flex items-start">
                                <span class="font-semibold mr-2">4.</span>
                                <span>Ready in approximately <strong>{{ $this->getEstimatedTime() }} minutes</strong></span>
                            </p>
                            @if($order_type === 'delivery')
                                <p class="flex items-start">
                                    <span class="font-semibold mr-2">5.</span>
                                    <span>🚚 Delivery in 15-20 minutes after preparation</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('menu', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            ← Back to Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>