<div class="min-h-screen bg-gray-50 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Cashier - Invoices</h1>
            <p class="text-gray-500 mt-1 text-sm">View and search all invoices</p>
        </div>

        <!-- Today Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today Paid</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">PKR {{ number_format($todayPaid, 0) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">PKR {{ number_format($todayPending, 0) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today Orders</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $todayCount }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="flex items-center gap-3" wire:ignore>
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input
                        id="dateRangePicker"
                        type="text"
                        readonly
                        placeholder="Select date range"
                        class="w-full pl-10 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                    >
                </div>
                <button onclick="setToday()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Today</button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('message') }}</div>
        @endif

        <!-- Invoices Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Restaurant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Table</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Payment</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $invoice->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->customer_name }}</div>
                                    @if($invoice->customer_phone)
                                        <div class="text-xs text-gray-500">{{ $invoice->customer_phone }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->restaurant->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->table_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">PKR {{ number_format($invoice->total_amount, 0) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                        @if($invoice->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($invoice->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($invoice->payment_status === 'refunded') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($invoice->payment_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="viewInvoice({{ $invoice->id }})" class="text-blue-600 hover:text-blue-800" title="View">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.invoice.print', $invoice->id) }}" target="_blank" class="text-gray-500 hover:text-gray-800 ml-2" title="Print">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="px-4 py-3 border-t">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

    <!-- Invoice Detail Modal -->
    @if($selectedInvoice)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeInvoice">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-xl font-bold text-gray-900">Invoice #{{ $selectedInvoice->order_number }}</h3>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.invoice.print', $selectedInvoice->id) }}" target="_blank"
                            class="text-gray-500 hover:text-gray-800" title="Print">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </a>
                        <button wire:click="closeInvoice" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <!-- Restaurant & Customer -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Restaurant</h4>
                            <p class="font-semibold text-gray-900">{{ $selectedInvoice->restaurant->name }}</p>
                            @if($selectedInvoice->restaurant->address)
                                <p class="text-sm text-gray-500">{{ $selectedInvoice->restaurant->address }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Customer</h4>
                            <p class="font-semibold text-gray-900">{{ $selectedInvoice->customer_name }}</p>
                            @if($selectedInvoice->customer_phone)
                                <p class="text-sm text-gray-500">{{ $selectedInvoice->customer_phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 p-3 bg-gray-50 rounded-lg text-sm">
                        <div>
                            <span class="text-gray-500">Date:</span>
                            <p class="font-medium text-gray-900">{{ $selectedInvoice->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Type:</span>
                            <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $selectedInvoice->order_type) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Table:</span>
                            <p class="font-medium text-gray-900">{{ $selectedInvoice->table_number ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Payment:</span>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                @if($selectedInvoice->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($selectedInvoice->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($selectedInvoice->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items -->
                    <table class="w-full mb-4">
                        <thead class="bg-gray-50 border-y">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Price</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($selectedInvoice->orderItems as $item)
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 text-center">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 text-right">PKR {{ number_format($item->item_price, 0) }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 text-right">PKR {{ number_format($item->total_price, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="flex justify-end">
                        <div class="w-56 text-sm">
                            <div class="flex justify-between py-1.5">
                                <span class="text-gray-500">Subtotal:</span>
                                <span class="font-medium">PKR {{ number_format($selectedInvoice->subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <span class="text-gray-500">Tax:</span>
                                <span class="font-medium">PKR {{ number_format($selectedInvoice->tax_amount, 0) }}</span>
                            </div>
                            @if($selectedInvoice->delivery_fee > 0)
                                <div class="flex justify-between py-1.5">
                                    <span class="text-gray-500">Delivery:</span>
                                    <span class="font-medium">PKR {{ number_format($selectedInvoice->delivery_fee, 0) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-2 border-t-2 border-gray-300 mt-1">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-green-600">PKR {{ number_format($selectedInvoice->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($selectedInvoice->notes)
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg text-sm">
                            <span class="font-semibold text-yellow-800">Notes:</span>
                            <span class="text-yellow-700">{{ $selectedInvoice->notes }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-4 pt-3 border-t">
                    <button wire:click="closeInvoice" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Close</button>
                </div>
            </div>
        </div>
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    @script
    <script>
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
        script.onload = () => initPicker();
        document.head.appendChild(script);

        function initPicker() {
            const el = document.getElementById('dateRangePicker');
            if (!el) return;

            window.__fp = flatpickr(el, {
                mode: 'range',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'M d, Y',
                defaultDate: [$wire.dateFrom, $wire.dateTo],
                onChange: function(dates) {
                    if (dates.length === 2) {
                        const from = dates[0].toISOString().split('T')[0];
                        const to = dates[1].toISOString().split('T')[0];
                        $wire.set('dateFrom', from);
                        $wire.set('dateTo', to);
                    }
                }
            });
        }

        window.setToday = function() {
            const today = new Date().toISOString().split('T')[0];
            $wire.set('dateFrom', today);
            $wire.set('dateTo', today);
            if (window.__fp) {
                window.__fp.setDate([today, today]);
            }
        };

        // Check for new orders every 10 seconds
        setInterval(() => {
            $wire.checkForUpdates();
        }, 10000);
    </script>
    @endscript
</div>
