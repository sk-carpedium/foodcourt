<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kitchen Daily Sales</h1>
                <p class="text-sm text-gray-500 mt-1">Revenue breakdown per restaurant / kitchen</p>
            </div>
        </div>

        <!-- Date Picker Bar -->
        <div class="bg-white rounded-lg border p-4 mb-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <button wire:click="previousDay" class="p-2 rounded-lg border hover:bg-gray-100 transition-colors" title="Previous Day">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <input type="date" wire:model.live="selectedDate"
                    class="rounded-lg border-gray-300 text-sm font-medium focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                <button wire:click="nextDay" class="p-2 rounded-lg border hover:bg-gray-100 transition-colors" title="Next Day">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</span>
                @if($selectedDate !== now()->toDateString())
                    <button wire:click="$set('selectedDate', '{{ now()->toDateString() }}')"
                        class="ml-2 px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        Today
                    </button>
                @else
                    <span class="ml-2 px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">Today</span>
                @endif
            </div>
        </div>

        <!-- Grand Totals -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg border p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Revenue</p>
                <p class="text-2xl font-bold text-green-600 mt-1">PKR {{ number_format($grandTotalRevenue, 0) }}</p>
            </div>
            <div class="bg-white rounded-lg border p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Orders</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $grandTotalOrders }}</p>
            </div>
            <div class="bg-white rounded-lg border p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Items Sold</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $grandTotalItems }}</p>
            </div>
        </div>

        @if($grandTotalRevenue == 0)
            <div class="bg-white rounded-lg border p-12 text-center">
                <div class="text-gray-300 text-6xl mb-4">📊</div>
                <h3 class="text-lg font-semibold text-gray-700">No sales on this date</h3>
                <p class="text-sm text-gray-500 mt-1">Select a different date or check back later.</p>
            </div>
        @endif

        <!-- Per-Kitchen Cards -->
        <div class="space-y-4">
            @foreach($kitchenSales as $kitchen)
                @if($kitchen['totalRevenue'] > 0)
                <div class="bg-white rounded-lg border" x-data="{ open: false }">
                    <!-- Summary Row -->
                    <div class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors" @click="open = !open">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                {{ substr($kitchen['restaurant']->name, 0, 2) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $kitchen['restaurant']->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $kitchen['orderCount'] }} orders · {{ $kitchen['totalItems'] }} items</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">PKR {{ number_format($kitchen['totalRevenue'], 0) }}</p>
                                <div class="flex gap-3 text-xs text-gray-500 mt-0.5">
                                    @if($kitchen['paymentBreakdown']['cash'] > 0)
                                        <span>💵 {{ number_format($kitchen['paymentBreakdown']['cash'], 0) }}</span>
                                    @endif
                                    @if($kitchen['paymentBreakdown']['card'] > 0)
                                        <span>💳 {{ number_format($kitchen['paymentBreakdown']['card'], 0) }}</span>
                                    @endif
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Expanded Item Breakdown -->
                    <div x-show="open" x-collapse class="border-t">
                        <div class="p-5">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Item Breakdown</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 uppercase border-b">
                                            <th class="pb-2 font-medium">Item</th>
                                            <th class="pb-2 font-medium text-center">Qty Sold</th>
                                            <th class="pb-2 font-medium text-right">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($kitchen['itemBreakdown'] as $item)
                                            <tr>
                                                <td class="py-2 text-gray-800">{{ $item['name'] }}</td>
                                                <td class="py-2 text-center text-gray-600">{{ $item['qty'] }}</td>
                                                <td class="py-2 text-right font-medium text-gray-900">PKR {{ number_format($item['revenue'], 0) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t font-semibold">
                                            <td class="pt-2 text-gray-900">Total</td>
                                            <td class="pt-2 text-center text-gray-900">{{ $kitchen['totalItems'] }}</td>
                                            <td class="pt-2 text-right text-green-600">PKR {{ number_format($kitchen['totalRevenue'], 0) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Restaurants with no sales (collapsed) -->
        @php $noSales = collect($kitchenSales)->where('totalRevenue', 0); @endphp
        @if($noSales->count() > 0 && $grandTotalRevenue > 0)
            <div class="mt-6 bg-white rounded-lg border p-5">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">No Sales Today</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($noSales as $k)
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded text-xs">{{ $k['restaurant']->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
