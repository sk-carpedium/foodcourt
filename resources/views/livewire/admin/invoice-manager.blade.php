<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Invoice Management</h1>
            <p class="mt-2 text-gray-600">Manage and track all invoices and payments</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-600 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-2">${{ number_format($pendingAmount, 2) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                        <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($totalInvoices) }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        placeholder="Order #, Name, Email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Restaurant</label>
                    <select wire:model.live="restaurantFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Restaurants</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" wire:model.live="dateFrom" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" wire:model.live="dateTo" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->customer_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $invoice->restaurant->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $invoice->created_at ? $invoice->created_at->format('d M Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($invoice->table_number)
                                            {{ $invoice->table_number }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $invoice->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $invoice->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $invoice->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $invoice->payment_status ? ucfirst($invoice->payment_status) : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="viewInvoice({{ $invoice->id }})" 
                                        class="text-blue-600 hover:text-blue-900" title="View Invoice">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-lg">
                                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="font-medium">No invoices found</p>
                                        <p class="text-sm mt-1">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

    <!-- Invoice Detail Modal -->
    @if($selectedInvoice)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeInvoice">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold text-gray-900">Invoice Details</h3>
                    <button wire:click="closeInvoice" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Invoice Content -->
                <div class="mt-6" id="invoice-content">
                    <!-- Invoice Header -->
                    <div class="flex justify-between mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">INVOICE</h2>
                            <p class="text-gray-600 mt-1">{{ $selectedInvoice->order_number }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xl font-bold text-gray-900">{{ $selectedInvoice->restaurant->name }}</h3>
                            @if($selectedInvoice->restaurant->address)
                                <p class="text-sm text-gray-600">{{ $selectedInvoice->restaurant->address }}</p>
                            @endif
                            @if($selectedInvoice->restaurant->phone)
                                <p class="text-sm text-gray-600">{{ $selectedInvoice->restaurant->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Customer & Invoice Info -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Bill To:</h4>
                            <p class="text-gray-700">{{ $selectedInvoice->customer_name }}</p>
                            @if($selectedInvoice->customer_email)
                                <p class="text-gray-600 text-sm">{{ $selectedInvoice->customer_email }}</p>
                            @endif
                            @if($selectedInvoice->customer_phone)
                                <p class="text-gray-600 text-sm">{{ $selectedInvoice->customer_phone }}</p>
                            @endif
                            @if($selectedInvoice->delivery_address)
                                <p class="text-gray-600 text-sm mt-2">{{ $selectedInvoice->delivery_address }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="mb-3">
                                <div class="text-sm text-gray-600">Invoice Date:</div>
                                <div class="font-semibold text-gray-900">
                                    {{ $selectedInvoice->created_at ? $selectedInvoice->created_at->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                            @if($selectedInvoice->table_number)
                                <div class="mb-3">
                                    <div class="text-sm text-gray-600">Table Number:</div>
                                    <div class="font-semibold text-gray-900">{{ $selectedInvoice->table_number }}</div>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Payment Status:</div>
                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full
                                    {{ $selectedInvoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $selectedInvoice->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $selectedInvoice->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $selectedInvoice->payment_status ? ucfirst($selectedInvoice->payment_status) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="w-full mb-8">
                        <thead class="bg-gray-50 border-y">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Item</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Qty</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Price</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($selectedInvoice->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">${{ number_format($item->item_price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="flex justify-end">
                        <div class="w-64">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">${{ number_format($selectedInvoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Tax:</span>
                                <span class="font-semibold">${{ number_format($selectedInvoice->tax_amount, 2) }}</span>
                            </div>
                            @if($selectedInvoice->delivery_fee > 0)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Delivery Fee:</span>
                                    <span class="font-semibold">${{ number_format($selectedInvoice->delivery_fee, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-3 border-t-2 border-gray-300">
                                <span class="text-lg font-bold text-gray-900">Total:</span>
                                <span class="text-lg font-bold text-green-600">${{ number_format($selectedInvoice->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($selectedInvoice->notes)
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Notes:</h4>
                            <p class="text-gray-700 text-sm">{{ $selectedInvoice->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button wire:click="closeInvoice" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
