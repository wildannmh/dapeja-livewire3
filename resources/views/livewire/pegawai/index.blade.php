<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Wadah Utama --}}
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg border border-neutral-200 dark:border-neutral-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Notifikasi Sukses --}}
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Tombol Aksi Utama --}}
                    <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah Pegawai
                    </button>

                    {{-- Input Pencarian --}}
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau NIP pegawai..." class="w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 mb-4">
                    
                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">NIP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Unit Kerja</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Gaji Pokok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Gaji Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @forelse ($pegawais as $pegawai)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->nip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $pegawai->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->jabatan->nama_jabatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->unitKerja->nama_unit }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($pegawai->gaji_total, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $pegawai->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold">Edit</button>
                                            <button wire:click="delete({{ $pegawai->id }})" wire:confirm="Anda yakin ingin menghapus data ini?" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 ml-4 font-semibold">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">Tidak ada data pegawai.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="mt-4 text-gray-700 dark:text-gray-300">
                        {{ $pegawais->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form dengan Dark Mode --}}
    @if ($showModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-data @click="$wire.set('showModal', false)" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-neutral-200 dark:border-neutral-700">
                <form wire:submit="save">
                    <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ $form->pegawai ? 'Edit Pegawai' : 'Tambah Pegawai' }}</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                                <input type="text" wire:model="form.nip" id="nip" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('form.nip') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                                <input type="text" wire:model="form.nama" id="nama" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('form.nama') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="gaji" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Pokok</label>
                                <input type="number" wire:model="form.gaji" id="gaji" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('form.gaji') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
                                <select wire:model="form.jabatan_id" id="jabatan_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('form.jabatan_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="unit_kerja_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Kerja</label>
                                <select wire:model="form.unit_kerja_id" id="unit_kerja_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach ($unitKerjas as $unitKerja)
                                        <option value="{{ $unitKerja->id }}">{{ $unitKerja->nama_unit }}</option>
                                    @endforeach
                                </select>
                                @error('form.unit_kerja_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-zinc-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t dark:border-zinc-700">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-zinc-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-700 font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-600 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>