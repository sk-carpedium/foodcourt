<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">Order Confirmed!</h1>
                <p class="mt-2 text-lg text-gray-600">Thank you for your order. We're preparing it now.</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                <h2 class="text-lg font-semibold mb-4 text-center text-gray-900">Order Details</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Order Number:</span>
                        <span class="font-mono text-lg font-bold text-blue-600">{{ $order->order_number }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Restaurant:</span>
                        <span class="font-semibold">{{ $order->restaurant->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Order Type:</span>
                        <span class="capitalize bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                            {{ str_replace('_', ' ', $order->order_type) }}
                        </span>
                    </div>
                    
                    @if($order->table_number)
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Table Number:</span>
                            <span class="font-semibold">{{ $order->table_number }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Payment Method:</span>
                        <span class="font-semibold">
                            @if($order->payment_method === 'cash') 💵 Cash
                            @elseif($order->payment_method === 'card') 💳 Card
                            @elseif($order->payment_method === 'bank_transfer') 🏦 Bank Transfer
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Payment Status:</span>
                        <span class="px-2 py-1 rounded text-sm font-medium 
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                    
                    @if($order->estimated_ready_at)
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Estimated Ready:</span>
                            <span class="font-semibold text-green-600">{{ $order->estimated_ready_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="text-lg font-semibold mb-4 text-center text-gray-900">Order Items</h3>
                <div class="space-y-3">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                            <div>
                                <span class="font-medium">{{ $item->quantity }}x {{ $item->item_name }}</span>
                                <div class="text-sm text-gray-600">PKR {{ number_format($item->item_price, 2) }} each</div>
                            </div>
                            <span class="font-semibold">PKR {{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-300 mt-4 pt-4">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">PKR {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($order->customer_phone || $order->customer_email)
                <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-medium text-blue-900 mb-2">Contact Information</h3>
                    <div class="text-sm text-blue-800 space-y-1">
                        @if($order->customer_phone)
                            <p>📞 We'll contact you at {{ $order->customer_phone }} when your order is ready</p>
                        @endif
                        @if($order->customer_email)
                            <p>✉️ Order confirmation sent to {{ $order->customer_email }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Next Steps -->
            <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                <h3 class="font-medium text-yellow-900 mb-2">What's Next?</h3>
                <div class="text-sm text-yellow-800 space-y-1">
                    @if($order->order_type === 'dine_in')
                        <p>🍽️ Please proceed to your table. Your food will be served when ready.</p>
                    @elseif($order->order_type === 'takeaway')
                        <p>🥡 Your order is being prepared. Please wait for pickup notification.</p>
                    @else
                        <p>🚚 Your order is being prepared and will be delivered to your address.</p>
                    @endif
                    @if($order->estimated_ready_at)
                        @php
                            $minutesRemaining = $order->estimated_ready_at->diffInMinutes(now(), false);
                        @endphp
                        @if($minutesRemaining > 0)
                            <p>⏰ Estimated preparation time: {{ ceil($minutesRemaining) }} minutes</p>
                        @elseif($order->status === 'ready' || $order->status === 'served' || $order->status === 'completed')
                            <p>✅ Your order is ready!</p>
                        @else
                            <p>👨‍🍳 Your order is being prepared and should be ready soon!</p>
                        @endif
                    @else
                        <p>⏰ Estimated preparation time: 15-25 minutes</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('menu', $order->restaurant->slug) }}" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Order Again
                </a>
                <a href="{{ route('home') }}" 
                   class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                    Back to Home
                </a>
            </div>

            <!-- Support -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Need help with your order? 
                    @if($order->restaurant->phone)
                        Contact {{ $order->restaurant->name }} at 
                        <a href="tel:{{ $order->restaurant->phone }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $order->restaurant->phone }}
                        </a>
                    @else
                        Contact the restaurant directly for assistance.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>