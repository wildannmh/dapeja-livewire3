<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan & Riwayat Cuti Seluruh Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Wadah Utama --}}
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-neutral-200 dark:border-neutral-700">
                
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{-- Input Pencarian --}}
                <div class="mb-4">
                    <label for="search" class="sr-only">Cari Pegawai</label>
                    <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="Cari berdasarkan nama pegawai..." class="w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                </div>
                
                {{-- Tabel Laporan --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tgl Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tgl Akhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Alasan</th>
                                {{-- KOLOM BARU UNTUK AKSI --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($riwayat as $cuti)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cuti->pegawai->nama ?? 'Pegawai Dihapus' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($cuti->tanggal_akhir)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays($cuti->tanggal_akhir) + 1 }} hari</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($cuti->alasan, 40) }}</td>
                                    {{-- TOMBOL HAPUS BARU --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button 
                                            wire:click="deleteCuti({{ $cuti->id }})" 
                                            wire:confirm="Anda yakin ingin menghapus data cuti ini secara permanen?" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-semibold">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Sesuaikan colspan menjadi 6 --}}
                                    <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data cuti yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                <div class="mt-4 text-gray-700 dark:text-gray-300">
                    {{ $riwayat->links() }}
                </div>
            </div>
        </div>
    </div>
</div>