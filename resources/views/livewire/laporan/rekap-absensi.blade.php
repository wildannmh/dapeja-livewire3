<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen & Rekap Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-neutral-200 dark:border-neutral-700">
                
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <div class="md:flex justify-between items-center mb-6 space-y-4 md:space-y-0">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div>
                            <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                            <select wire:model.live="bulan" id="bulan" class="mt-1 block w-full md:w-auto rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200">
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option> @endfor
                            </select>
                        </div>
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                            <select wire:model.live="tahun" id="tahun" class="mt-1 block w-full md:w-auto rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200">
                                @for ($i = now()->year; $i >= now()->year - 2; $i--) <option value="{{ $i }}">{{ $i }}</option> @endfor
                            </select>
                        </div>
                    </div>
                    {{-- Tombol Aksi Dinamis --}}
                    <div class="flex items-center gap-2">
                        @if($isEditing)
                            {{-- Tombol saat mode edit aktif --}}
                            <button wire:click="save" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                            <button wire:click="toggleEditMode" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </button>
                        @else
                            {{-- Tombol saat mode lihat aktif --}}
                            <button wire:click="toggleEditMode" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Edit Data Absensi
                            </button>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                     <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau NIP pegawai..." class="w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Pegawai</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hari Kerja</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cuti</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sakit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Izin</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Alpha</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase bg-green-50 dark:bg-green-900/50">Hadir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($absensiData as $id => $data)
                                @php
                                    $hadir = $data['hari_kerja'] - $data['cuti'] - $data['sakit'] - $data['izin'] - $data['alpha'];
                                @endphp
                                <tr>
                                    <td class="px-6 py-4"><div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $data['nama'] }}</div><div class="text-sm text-gray-500 dark:text-gray-400">{{ $data['nip'] }}</div></td>
                                    
                                    {{-- Kolom Hari Kerja sekarang bisa diedit --}}
                                    <td class="p-2 text-center">
                                        @if($isEditing)
                                            <input type="number" min="0" wire:model.live="absensiData.{{ $id }}.hari_kerja" class="w-20 text-center rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                        @else
                                            <span class="px-6 py-4 text-sm">{{ $data['hari_kerja'] }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center text-sm text-purple-600 dark:text-purple-400">{{ $data['cuti'] }}</td>
                                    
                                    {{-- Kolom Sakit, Izin, Alpha dibuat dinamis --}}
                                    <td class="p-2 text-center">
                                        @if($isEditing)
                                            <input type="number" min="0" wire:model.live="absensiData.{{ $id }}.sakit" class="w-16 text-center rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                        @else
                                            <span class="px-6 py-4 text-sm">{{ $data['sakit'] }}</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-center">
                                        @if($isEditing)
                                            <input type="number" min="0" wire:model.live="absensiData.{{ $id }}.izin" class="w-16 text-center rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                        @else
                                            <span class="px-6 py-4 text-sm">{{ $data['izin'] }}</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-center">
                                        @if($isEditing)
                                            <input type="number" min="0" wire:model.live="absensiData.{{ $id }}.alpha" class="w-16 text-center rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                        @else
                                            <span class="px-6 py-4 text-sm">{{ $data['alpha'] }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center text-sm font-bold {{ $hadir < 0 ? 'text-red-500' : 'text-green-600 dark:text-green-400' }}">{{ $hadir }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4">Tidak ada data pegawai yang cocok dengan pencarian.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>