<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="md:mb-10 mb-5 flex flex-col justify-between">
            <h1 class="text-xl md:text-3xl font-semibold">Broker Summary</h1>
            <p class="text-sm text-gray-500 mt-1">Market intelligence and bandarmology analysis.</p>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 md:mb-2">Daily Transactions</h2>
                
                <div x-data="{ filter: false }" class="w-full">
                    <div class="flex flex-col gap-3">
                        
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
                            <div class="w-full md:w-2/3">
                                <label class="text-xs text-gray-500 font-semibold mb-1 block"></label>
                                <input 
                                    type="text" 
                                    wire:model.live.300ms="search" 
                                    placeholder="Search (e.g. BBCA or YP)" 
                                    class="rounded-lg border border-gray-300 text-sm font-light w-full uppercase"
                                >
                            </div>

                            <div class="md:hidden flex justify-between items-center gap-2"> 
                                <button @click="filter = !filter" type="button" class="py-2 px-3 rounded-lg border border-gray-300 text-sm font-light flex-1 flex justify-between items-center bg-gray-50">
                                    <span>Date Filters</span>
                                    <i class="fa-solid ml-2" :class="filter ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                </button>
                                
                                <button wire:click="syncData" wire:loading.attr="disabled" class="bg-indigo-600 text-white rounded-lg p-2 shadow-sm">
                                    <span wire:loading.remove><i class="fa-solid fa-sync"></i></span>
                                    <span wire:loading>...</span>
                                </button>
                            </div>

                            <div :class="filter ? 'flex' : 'hidden md:flex'" class="flex flex-col md:flex-row gap-3 w-full md:w-2/3 md:items-end">
                                
                                <div class="w-full md:w-2/5">
                                    <label class="text-xs text-gray-500 font-semibold mb-1 block">START DATE</label>
                                    <input type="date" wire:model.live="startDate" class="rounded-lg border border-gray-300 text-sm font-light w-full">
                                </div>

                                <div class="w-full md:w-2/5">
                                    <label class="text-xs text-gray-500 font-semibold mb-1 block">END DATE</label>
                                    <input type="date" wire:model.live="endDate" class="rounded-lg border border-gray-300 text-sm font-light w-full">
                                </div>

                                <div class="hidden md:block">
                                    <button 
                                        wire:click="syncData" 
                                        wire:loading.attr="disabled"
                                        class="h-[38px] inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm whitespace-nowrap"
                                    >
                                        <span wire:loading.remove wire:target="syncData">Sync Data</span>
                                        <span wire:loading wire:target="syncData">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto max-h-[800px] overflow-y-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head wire:click="sort('date')" class="cursor-pointer group hover:text-indigo-600">
                                <div class="flex items-center gap-1">
                                    Date
                                    @if($sortBy === 'date') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </div>
                            </x-table.head>
                            <x-table.head>Ticker</x-table.head>
                            <x-table.head>Broker</x-table.head>
                            <x-table.head wire:click="sort('net_vol')" class="cursor-pointer group hover:text-indigo-600">
                                <div class="flex items-center justify-end gap-1">
                                    Net Vol
                                    @if($sortBy === 'net_vol') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </div>
                            </x-table.head>
                            
                            <x-table.head class="text-right">Buy Value</x-table.head>
                            <x-table.head class="text-right text-gray-500">Buy Avg</x-table.head> <x-table.head class="text-right">Sell Value</x-table.head>
                            <x-table.head class="text-right text-gray-500">Sell Avg</x-table.head> <x-table.head class="text-center">Status</x-table.head>
                        </x-table.row>
                    
                        <tr class="bg-indigo-50 border-b border-indigo-100">
                            {{-- Colspan 3: Date, Ticker, Broker --}}
                            <td colspan="3" class="px-6 py-3 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                TOTAL SUMMARY
                            </td>
                            
                            {{-- Total Net Vol --}}
                            <td class="px-6 py-3 text-right font-mono text-xs font-bold {{ $totalNetVol > 0 ? 'text-green-700' : ($totalNetVol < 0 ? 'text-red-700' : 'text-gray-600') }}">
                                {{ number_format($totalNetVol) }}
                            </td>
                    
                            {{-- Total Buy Value --}}
                            <td class="px-6 py-3 text-right font-mono text-xs font-bold text-gray-800">
                                Rp {{ number_format($totalBuyVal, 0, ',', '.') }}
                            </td>

                            {{-- Total Buy Avg (Placeholder) --}}
                            <td class="px-6 py-3 text-right font-mono text-xs text-gray-400">
                                -
                            </td>
                    
                            {{-- Total Sell Value --}}
                            <td class="px-6 py-3 text-right font-mono text-xs font-bold text-gray-800">
                                Rp {{ number_format($totalSellVal, 0, ',', '.') }}
                            </td>

                            {{-- Total Sell Avg (Placeholder) --}}
                            <td class="px-6 py-3 text-right font-mono text-xs text-gray-400">
                                -
                            </td>
                    
                            {{-- Total Status --}}
                            <td class="px-6 py-3 text-center">
                                @if($summaries->isNotEmpty())
                                    @if($totalNetVol > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-green-200 text-green-900 border border-green-300 shadow-sm">
                                            AKUM
                                        </span>
                                    @elseif($totalNetVol < 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-200 text-red-900 border border-red-300 shadow-sm">
                                            DIST
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-gray-200 text-gray-800 border border-gray-300 shadow-sm">
                                            NETRAL
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    </x-table.header>
                    <x-table.body>
                        @forelse($summaries as $id => $row)
                            <x-table.row wire:key="broker-{{ $row->id }}">
                                <x-table.data class="whitespace-nowrap">
                                    {{ $row->date->format('d M Y') }}
                                </x-table.data>

                                <x-table.data>
                                    <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                        {{ $row->ticker }}
                                    </span>
                                </x-table.data>

                                <x-table.data>
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-xs font-bold text-gray-700 border border-gray-200">
                                        {{ $row->broker_code }}
                                    </span>
                                </x-table.data>

                                <x-table.data class="text-right font-mono {{ $row->net_vol > 0 ? 'text-green-600' : ($row->net_vol < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                    {{ number_format($row->net_vol) }}
                                </x-table.data>

                                <x-table.data class="text-right font-mono">
                                    Rp {{ number_format($row->buy_val, 0, ',', '.') }}
                                </x-table.data>

                                {{-- Buy Avg Data --}}
                                <x-table.data class="text-right font-mono text-gray-600 text-xs">
                                    {{ $row->buy_avg > 0 ? number_format($row->buy_avg, 0, ',', '.') : '-' }}
                                </x-table.data>

                                <x-table.data class="text-right font-mono">
                                    Rp {{ number_format($row->sell_val, 0, ',', '.') }}
                                </x-table.data>

                                {{-- Sell Avg Data --}}
                                <x-table.data class="text-right font-mono text-gray-600 text-xs">
                                    {{ $row->sell_avg > 0 ? number_format($row->sell_avg, 0, ',', '.') : '-' }}
                                </x-table.data>

                                <x-table.data class="text-center">
                                    @if($row->net_vol > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">AKUM</span>
                                    @elseif($row->net_vol < 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">DIST</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">NETRAL</span>
                                    @endif
                                </x-table.data>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                {{-- Updated Colspan to 9 (Date, Ticker, Broker, NetVol, BuyVal, BuyAvg, SellVal, SellAvg, Status) --}}
                                <x-table.data colspan="9">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <span class="text-base font-medium text-gray-500">No Data Found</span>
                                    </div>
                                </x-table.data>
                            </x-table.row>
                        @endforelse
                    </x-table.body>
                </x-table.table>
            </div>
            
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 text-xs text-gray-500 text-right">
                Showing all {{ count($summaries) }} records
            </div>

        </div>
    </main>
</div>