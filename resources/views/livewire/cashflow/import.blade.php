<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
        <div class="mb-10 flex justify-between">
            <h1 class="text-3xl font-semibold">Import Data</h1>
        </div>

        <div id="FileUpload" class="relative">
            <input type="file"
                class="absolute inset-0 z-50 m-0 p-0 w-full h-full outline-none opacity-0 cursor-pointer"
                x-on:dragover="$el.classList.add('active')"
                x-on:dragleave="$el.classList.remove('active')"
                x-on:drop="$el.classList.remove('active')"
                wire:model.lazy="import"
            />
                <x-content-upload
                    class="!space-y-2"
                    classTitle="hidden"
                    classOr="hidden"
                    classButton="hidden"
                    classDescription="hidden"
                    classMaxFile="hidden"
                >
                <div class="flex flex-col justify-center space-y-2">
                    <div class="px-4 text-gray-900 text-xl text-center font-bold">
                        Click or Drop file in the area above to start upload
                    </div>
                    <div class="px-4 text-xs text-gray-400 text-center">
                        Supported file types: CSV and TXT <br>
                        Max file size: 5 MB
                    </div>
                </div>
            </x-content-upload>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Import</h2>
                <div class="relative">
                    {{-- <select x-model="transactionFilter" @change="filterTransactions" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select> --}}
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head>Name</x-table.head>
                            <x-table.head>Size</x-table.head>
                            <x-table.head>Uploaded Date</x-table.head>
                            <x-table.head>Action</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse($fileImports as $file)
                            <x-table.row class="even:bg-gray-50 odd:bg-white">
                                <x-table.data wire:click="download({{ $file->id }})" style="cursor: pointer;" class="">
                                    <div class="flex gap-4 items-center text-rose-900 hover:text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.25 2.5C14.25 2.4337 14.2237 2.37011 14.1768 2.32322C14.1299 2.27634 14.0663 2.25 14 2.25H7C6.27065 2.25 5.57118 2.53973 5.05546 3.05546C4.53973 3.57118 4.25 4.27065 4.25 5V19C4.25 19.7293 4.53973 20.4288 5.05546 20.9445C5.57118 21.4603 6.27065 21.75 7 21.75H17C17.7293 21.75 18.4288 21.4603 18.9445 20.9445C19.4603 20.4288 19.75 19.7293 19.75 19V9.147C19.75 9.0807 19.7237 9.01711 19.6768 8.97022C19.6299 8.92334 19.5663 8.897 19.5 8.897H15C14.8011 8.897 14.6103 8.81798 14.4697 8.67733C14.329 8.53668 14.25 8.34591 14.25 8.147V2.5ZM15 12.25C15.1989 12.25 15.3897 12.329 15.5303 12.4697C15.671 12.6103 15.75 12.8011 15.75 13C15.75 13.1989 15.671 13.3897 15.5303 13.5303C15.3897 13.671 15.1989 13.75 15 13.75H9C8.80109 13.75 8.61032 13.671 8.46967 13.5303C8.32902 13.3897 8.25 13.1989 8.25 13C8.25 12.8011 8.32902 12.6103 8.46967 12.4697C8.61032 12.329 8.80109 12.25 9 12.25H15ZM15 16.25C15.1989 16.25 15.3897 16.329 15.5303 16.4697C15.671 16.6103 15.75 16.8011 15.75 17C15.75 17.1989 15.671 17.3897 15.5303 17.5303C15.3897 17.671 15.1989 17.75 15 17.75H9C8.80109 17.75 8.61032 17.671 8.46967 17.5303C8.32902 17.3897 8.25 17.1989 8.25 17C8.25 16.8011 8.32902 16.6103 8.46967 16.4697C8.61032 16.329 8.80109 16.25 9 16.25H15Z" fill="currentColor"/>
                                            <path d="M15.75 2.824C15.75 2.64 15.943 2.523 16.086 2.638C16.207 2.736 16.316 2.85 16.409 2.98L19.422 7.177C19.49 7.273 19.416 7.397 19.298 7.397H16C15.9337 7.397 15.8701 7.37066 15.8232 7.32378C15.7763 7.2769 15.75 7.21331 15.75 7.147V2.824Z" fill="currentColor"/>
                                        </svg>
                                        <h1>{{$file->filename}}</h1>
                                    </div>
                                </x-table.data>
                                <x-table.data>{{ $file->size }}</x-table.data>
                                <x-table.data>{{ $file->created_at }}</x-table.data>
                                <x-table.data>
                                    <button wire:click.stop="delete({{ $file->id }})"
                                        style="cursor: pointer;" 
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </x-table.data>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.data colspan="4">
                                    <div class="text-center">No Data Found</div>
                                </x-table.data>
                            </x-table.row>
                        @endforelse
                    </x-table.body>
                </x-table.table>
            </div>
        </div>
    </main>
</div>
