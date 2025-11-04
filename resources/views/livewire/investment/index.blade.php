<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="md:mb-10 mb-5 flex md:flex-row flex-col justify-between gap-5">
            <h1 class="text-xl md:text-3xl font-semibold">Manage Investment</h1>
            <button wire:click="addNewInvestment" class="rounded-lg px-4 py-2 border border-bright-gray cursor-pointer">Add New Investment</button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Assets</h2>
                <div class="relative">
                    <select wire:model.lazy="filterCategory" class="border border-bright-gray text-gray-700 py-2 px-4 pr-8 rounded-lg text-sm">
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
                            <x-table.head>P&L</x-table.head>
                            <x-table.head>Investment Balance</x-table.head>
                            <x-table.head>Current Value</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($investments as $id => $investment)
                            <x-table.row wire:key="investment-{{ $id }}">
                                <x-table.data>
                                    <select wire:model.lazy="investments.{{ $id }}.investment_code_id" class="rounded-full border-none ring-0 text-sm font-light">
                                        <option value="">Select Investment</option>
                                        @foreach ($investmentCodes as $code)
                                            <option value="{{ $code->id }}">{{ $code->name }}</option>
                                        @endforeach
                                    </select>
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $this->getCategoryColor($investment['investment_code']['investment_category_id'] ?? null) }}">
                                        {{ $investment['investment_code']['category']['name'] ?? '' }}
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
                                    {{ $investment['investment_code']['unit'] ?? '' }}
                                </x-table.data>
                                <x-table.data>
                                    <input
                                        type="text"
                                        wire:model.defer="investments.{{ $id }}.average_buying_price"

                                        x-data="{
                                            parse(val) {
                                                if (!val) return null;
                                                let clean = val.toString()
                                                    .replace(/Rp\s/g, '')  // Hapus 'Rp '
                                                    .replace(/\./g, '')    // Hapus titik ribuan
                                                    .replace(/,/g, '.');   // Ubah koma desimal jadi titik
                                                
                                                let num = parseFloat(clean);
                                                return isNaN(num) ? null : num;
                                            },

                                            format(val) {
                                                let num = parseFloat(val); 
                                                if (isNaN(num)) return '';

                                                let formatted = new Intl.NumberFormat('id-ID', {
                                                    minimumFractionDigits: 0, 
                                                    maximumFractionDigits: 5 // Izinkan hingga 5 angka desimal
                                                }).format(num);
                                                
                                                return 'Rp ' + formatted;
                                            },

                                            formatForInput(val) {
                                                let num = parseFloat(val);
                                                if (isNaN(num)) return '';
                                                
                                                return num.toString().replace('.', ','); 
                                            }
                                        }"

                                        x-init="$el.value = format($wire.get('investments.{{ $id }}.average_buying_price'))"
                                        
                                        @focus="$el.value = formatForInput($wire.get('investments.{{ $id }}.average_buying_price'))"
                                        
                                        @blur="
                                            let numericValue = parse($el.value);
                                            $wire.set('investments.{{ $id }}.average_buying_price', numericValue);
                                            $el.value = format(numericValue);
                                        "
                                        
                                        class="rounded border-none ring-0 text-sm font-light"
                                    />
                                </x-table.data>
                                <x-table.data>
                                    <p>Rp {{ number_format($investment['latest_market_price']['current_price'] ?? 0, 2, ',', '.') }},-</p>
                                </x-table.data>
                                <x-table.data>
                                    @php
                                        $percentageChange = $this->getPnL(
                                            $investment['average_buying_price'] ?? 0, 
                                            $investment['latest_market_price']['current_price'] ?? 0,
                                            $investment['investment_code']['unit'] ?? 0, 
                                            $investment['amount'] ?? 0
                                        );
                                    @endphp
                                    <p class="{{ $percentageChange < 0 ? 'text-red-500' : 'text-green-700' }} font-semibold">
                                        {{ $percentageChange }}%
                                    </p>
                                </x-table.data>
                                <x-table.data>
                                    @php
                                        $buyingPrice = $this->getTotalValue(
                                            $investment['average_buying_price'] ?? 0, 
                                            $investment['investment_code']['unit'] ?? 0,
                                            $investment['amount'] ?? 0
                                        );
                                    @endphp
                                    <p class="font-semibold">
                                        Rp {{ number_format($buyingPrice, 2, ',', '.') }},-
                                    </p>
                                </x-table.data>
                                <x-table.data>
                                    @php
                                        $buyingPrice = $this->getTotalValue(
                                            $investment['latest_market_price']['current_price'] ?? 0, 
                                            $investment['investment_code']['unit'] ?? 0,
                                            $investment['amount'] ?? 0
                                        );
                                    @endphp
                                    <p class="font-semibold">
                                        Rp {{ number_format($buyingPrice, 2, ',', '.') }},-
                                    </p>
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <button wire:click="delete({{ $investment['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </x-table.data>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.data colspan="10">
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
