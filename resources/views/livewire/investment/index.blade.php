<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10 flex justify-between">
            <h1 class="text-3xl font-semibold">Manage Investment</h1>
            <button wire:click="addNewInvestment" class="rounded-lg px-4 py-2 border border-quick-silver cursor-pointer">Add New Investment</button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Assets</h2>
                <div class="relative">
                    <select wire:model.lazy="filterCategory" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="">All Assets</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head>Investment Name</x-table.head>
                            <x-table.head>Category</x-table.head>
                            <x-table.head>Amount</x-table.head>
                            <x-table.head>Unit</x-table.head>
                            <x-table.head>Avg. Buying Price</x-table.head>
                            <x-table.head>Current Price</x-table.head>
                            <x-table.head>Investment Balance</x-table.head>
                            <x-table.head>Current Value</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($investments as $id => $investment)
                            <x-table.row>
                                <x-table.data>
                                    <select wire:model.lazy="investments.{{ $id }}.investment_code.id" class="rounded-full border-none ring-0 text-sm font-light">
                                        @foreach ($investmentCodes as $code)
                                            <option value="{{ $code->id }}">{{ $code->name }}</option>
                                        @endforeach
                                    </select>
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $investment['investment_code']['category']['name'] }}
                                    </span>
                                </x-table.data>
                                <x-table.data>
                                    <input type="text"
                                        wire:model.blur="investments.{{ $id }}.amount"
                                        class="rounded border-none ring-0 text-sm font-light w-20"
                                        step="any"
                                        x-data="{
                                            cleanValue(val) {
                                                if (!val && val !== 0) return '';

                                                let valueAsDot = val.toString().replace(',', '.');
                                                let num = parseFloat(valueAsDot);
                                                
                                                if (isNaN(num)) return '';
                                                
                                                let cleanString = num.toString();
                                                
                                                return cleanString.replace('.', ',');
                                            }
                                        }"
                                        x-init="$el.value = cleanValue($wire.get('investments.{{ $id }}.amount'))"
                                        @blur="$el.value = cleanValue($el.value)"
                                    >
                                </x-table.data>
                                <x-table.data class="text-center">
                                    {{ $investment['investment_code']['unit'] }}
                                </x-table.data>
                                <x-table.data>
                                    <input
                                        type="text"
                                        x-data="{ average_buying_price: '{{ number_format($investment['average_buying_price'], 0, ',', '.') }}' }"
                                        x-init="
                                            format();
                                            function format() {
                                                average_buying_price = 'Rp ' + new Intl.NumberFormat('id-ID').format(average_buying_price.replace(/[^\d]/g, ''));
                                            }
                                            $el.value = average_buying_price;
                                            $el.addEventListener('input', (e) => {
                                                average_buying_price = e.target.value;
                                                format();
                                                $el.value = average_buying_price;
                                            });
                                        "
                                        x-on:blur="$wire.set('investments.{{ $id }}.average_buying_price', average_buying_price.replace(/[^\d]/g, ''))"
                                        class="rounded border-none ring-0 text-sm font-light"
                                    />
                                </x-table.data>
                                <x-table.data>
                                    <input type="number" 
                                        wire:model="investments.{{ $id }}.latest_market_price.current_price" 
                                        step="any"
                                        class="rounded border-none ring-0 text-sm font-light"
                                    >
                                </x-table.data>
                                <x-table.data>
                                    @php
                                        if($investment['investment_code']['unit'] == 'lot') {
                                            $buyingPrice = $investment['average_buying_price']*100;
                                        } else {
                                            $buyingPrice = $investment['average_buying_price'];
                                        }
                                    @endphp
                                    <p>Rp {{ number_format($buyingPrice*$investment['amount'], 2, ',', '.') }},-</p>
                                </x-table.data>
                                <x-table.data>
                                    @php
                                        if($investment['investment_code']['unit'] == 'lot') {
                                            $currentPrice = $investment['latest_market_price']['current_price']*100;
                                        } else {
                                            $currentPrice = $investment['latest_market_price']['current_price'];
                                        }
                                    @endphp
                                    <p>Rp {{ number_format($currentPrice*$investment['amount'], 2, ',', '.') }},-</p>
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <button wire:click="delete({{ $investment['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </x-table.data>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.data colspan="5">
                                    <div class="text-center">No Data Found</div>
                                </x-table.data>
                            </x-table.row>
                        @endforelse
                    </x-table.body>
                </x-table.table>

                {{-- {{ $investments->links() }} --}}
            </div>
        </div>
    </main>
</div>
