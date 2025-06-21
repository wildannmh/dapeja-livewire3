<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Input Cuti Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-6" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
    
                    @if (!is_null($sisaCuti))
                        <div class="bg-blue-100 dark:bg-blue-900/50 border-l-4 border-blue-500 dark:border-blue-600 text-blue-700 dark:text-blue-200 p-4 mb-6" role="alert">
                            <p class="font-bold">Jatah Cuti Pegawai Terpilih</p>
                            {{-- Tampilkan tahun dari tanggal mulai, atau tahun ini jika tanggal mulai kosong --}}
                            <p>Sisa jatah cuti untuk tahun <strong>{{ $tanggal_mulai ? \Carbon\Carbon::parse($tanggal_mulai)->year : now()->year }}</strong> adalah: <strong>{{ $sisaCuti }} hari</strong>.</p>
                        </div>
                    @endif
    
                    <form wire:submit="save">
                        <div class="space-y-6">
                            <div>
                                <label for="pegawai_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Pegawai</label>
                                <select wire:model.live="pegawai_id" id="pegawai_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">-- Pilih seorang pegawai --</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} (NIP: {{ $pegawai->nip }})</option>
                                    @endforeach
                                </select>
                                @error('pegawai_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
    
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai Cuti</label>
                                    {{-- Tambahkan .live agar sisa cuti langsung update --}}
                                    <input type="date" wire:model.live="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ !$pegawai_id ? 'disabled' : '' }}>
                                    @error('tanggal_mulai') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir Cuti</label>
                                    <input type="date" wire:model="tanggal_akhir" id="tanggal_akhir" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ !$pegawai_id ? 'disabled' : '' }}>
                                    @error('tanggal_akhir') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
    
                            <div>
                                <label for="alasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Cuti</label>
                                <textarea wire:model="alasan" id="alasan" rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ !$pegawai_id ? 'disabled' : '' }}></textarea>
                                @error('alasan') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
    
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50" {{ !$pegawai_id ? 'disabled' : '' }}>
                                    Simpan Data Cuti
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>