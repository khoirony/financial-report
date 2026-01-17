<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Import Rekening Koran</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Upload file PDF (Bank Statement) Anda. AI akan otomatis mendeteksi dan mengkategorikan transaksi.
                </p>
            </div>
        </div>

        <div 
            x-data="{ isDropping: false }"
            x-on:dragover.prevent="isDropping = true"
            x-on:dragleave.prevent="isDropping = false"
            x-on:drop.prevent="isDropping = false"
            class="relative w-full group"
        >
            <div 
                class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-xl transition-all duration-200 ease-in-out bg-gray-50"
                :class="isDropping ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:bg-gray-100'"
            >
                
                <div wire:loading.flex wire:target="import" class="absolute inset-0 z-50 flex-col items-center justify-center bg-white/90 rounded-xl backdrop-blur-sm">
                    <svg class="w-12 h-12 text-blue-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-sm font-medium text-gray-700 animate-pulse">Mengupload & Menganalisa dengan AI...</p>
                    <p class="text-xs text-gray-500 mt-1">Mohon tunggu, jangan tutup halaman ini.</p>
                </div>

                <input 
                    type="file" 
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer"
                    wire:model="import"
                    accept=".pdf"
                    wire:loading.attr="disabled"
                />

                <div wire:loading.remove wire:target="import" class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                    <div class="p-4 mb-3 rounded-full bg-gray-100 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                        <svg aria-hidden="true" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <p class="mb-2 text-sm text-gray-700">
                        <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                    </p>
                    <p class="text-xs text-gray-500">PDF Only (Bank Statement) - Max 10 MB</p>
                </div>
            </div>

            @error('import') 
                <div class="mt-3 p-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2 animate-pulse">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                <h2 class="text-base font-semibold text-gray-900">Riwayat Import File</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                    {{ count($fileImports) }} Files
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded At</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($fileImports as $file)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center cursor-pointer" wire:click="download({{ $file->id }})">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 transition-colors">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 group-hover:text-blue-600 truncate max-w-xs" title="{{ $file->filename }}">
                                                {{ Str::limit($file->filename, 40) }}
                                            </div>
                                            <div class="text-xs text-gray-400">Click to download</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $this->formatSize($file->size) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($file->created_at)->format('d M Y, H:i') }}
                                    <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($file->created_at)->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button 
                                        wire:click.stop="delete({{ $file->id }})"
                                        wire:confirm="Yakin ingin menghapus file ini? Transaksi yang sudah diimport tidak akan terhapus."
                                        class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50"
                                        title="Hapus File"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 rounded-full bg-gray-50 mb-3">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-900">Belum ada file</h3>
                                        <p class="text-gray-500 text-sm mt-1">Upload file pertama Anda untuk melihat history.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>