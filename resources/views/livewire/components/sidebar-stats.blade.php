<div class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
    <div class="space-y-1">
        @if($stats['user_restaurant'])
            <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 pb-1 mb-2">
                <span class="font-medium">Restaurant:</span>
                <span class="font-semibold text-blue-600 dark:text-blue-400 text-[10px]">{{ Str::limit($stats['user_restaurant'], 12) }}</span>
            </div>
        @endif
        
        <div class="flex justify-between">
            <span>Menu Items:</span>
            <span class="font-semibold">{{ $stats['menu_items'] }}</span>
        </div>
        
        <div class="flex justify-between">
            <span>Today's Orders:</span>
            <span class="font-semibold text-green-600 dark:text-green-400">{{ $stats['todays_orders'] }}</span>
        </div>
        
        @if($stats['pending_orders'] > 0)
        <div class="flex justify-between">
            <span>🔔 Pending:</span>
            <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_orders'] }}</span>
        </div>
        @endif
        
        @if($stats['preparing_orders'] > 0)
        <div class="flex justify-between">
            <span>🔥 Preparing:</span>
            <span class="font-semibold text-orange-600 dark:text-orange-400">{{ $stats['preparing_orders'] }}</span>
        </div>
        @endif
        
        @if($stats['ready_orders'] > 0)
        <div class="flex justify-between">
            <span>✅ Ready:</span>
            <span class="font-semibold text-green-600 dark:text-green-400">{{ $stats['ready_orders'] }}</span>
        </div>
        @endif
        
        @if(!$stats['user_restaurant'])
        <div class="flex justify-between">
            <span>Restaurants:</span>
            <span class="font-semibold">{{ $stats['restaurants'] }}</span>
        </div>
        @endif
    </div>
</div>