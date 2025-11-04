<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10">
            <h1 class="text-xl md:text-3xl font-semibold">Manage Cashflow</h1>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Cashflow Types</h2>
    
                <div class="relative">
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head class="w-10">id</x-table.head>
                            <x-table.head>Name</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($types as $id => $type)
                            <x-table.row wire:key="type-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="types.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $type['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
    
                {{-- {{ $category->links() }} --}}
            </div>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Cashflow Categories</h2>
    
                <div class="relative">
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head class="w-10">id</x-table.head>
                            <x-table.head>Name</x-table.head>
                            <x-table.head>Type</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($categories as $id => $category)
                            <x-table.row wire:key="category-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="categories.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <select wire:model.lazy="categories.{{ $id }}.cashflow_type_id" class="rounded-full border-none ring-0 text-sm font-light {{ $category['cashflow_type_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        @foreach ($types as $type)
                                            <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category['cashflow_type_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    </span>
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $category['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
    
                {{-- {{ $category->links() }} --}}
            </div>
        </div>
    </main>
</div>
