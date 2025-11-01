<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10 flex justify-between">
            <h1 class="text-3xl font-semibold">Manage Cashflow</h1>
            <div>
                <button wire:click="addNewCashflow" class="rounded-lg px-4 py-2 border border-quick-silver cursor-pointer">Add New Cashflow</button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Transactions</h2>
                <div class="relative">
                    <select wire:model.lazy="filterCategory" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="">All Transactions</option>
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
                            <x-table.head>Date</x-table.head>
                            <x-table.head>Category</x-table.head>
                            <x-table.head>Amount</x-table.head>
                            <x-table.head>Description</x-table.head>
                            <x-table.head>Source</x-table.head>
                            <x-table.head>Destination</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($cashflows as $id => $cashflow)
                            <x-table.row wire:key="investment-{{ $id }}">
                                <x-table.data>
                                    <input type="date" wire:model.lazy="cashflows.{{ $id }}.transaction_date" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashflow['cashflow_category_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        <select wire:model.lazy="cashflows.{{ $id }}.cashflow_category_id" class="rounded-full border-none ring-0 text-sm font-light {{ $cashflow['cashflow_category_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </x-table.data>
                                <x-table.data>
                                    <input
                                        type="text"
                                        wire:model.defer="cashflows.{{ $id }}.amount"

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

                                        x-init="$el.value = format($wire.get('cashflows.{{ $id }}.amount'))"
                                        
                                        @focus="$el.value = formatForInput($wire.get('cashflows.{{ $id }}.amount'))"
                                        
                                        @blur="
                                            let numericValue = parse($el.value);
                                            $wire.set('cashflows.{{ $id }}.amount', numericValue);
                                            $el.value = format(numericValue);
                                        "
                                        
                                        class="rounded border-none ring-0 text-sm font-light"
                                    />
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="cashflows.{{ $id }}.description" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="cashflows.{{ $id }}.source_account" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="cashflows.{{ $id }}.destination_account" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <button wire:click="delete({{ $cashflow['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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

                {{-- {{ $cashflows->links() }} --}}
            </div>
        </div>
    </main>
</div>
