<div title="{{ __('Dashboard') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Kartu Statistik 1: Jumlah Pegawai --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                    <div class="bg-blue-500/10 dark:bg-blue-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-blue-500"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Pegawai</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahPegawai }}</p>
                    </div>
                </div>

                {{-- Kartu Statistik 2: Jumlah Unit Kerja --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                     <div class="bg-green-500/10 dark:bg-green-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-2 text-green-500"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Unit Kerja</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahUnitKerja }}</p>
                    </div>
                </div>
                
                {{-- Kartu Statistik 3: Jumlah Jabatan --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                    <div class="bg-orange-500/10 dark:bg-orange-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase text-orange-500"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Jabatan</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahJabatan }}</p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                
                {{-- Kolom Kiri: Grafik (Versi Final dengan wire:ignore) --}}
                {{-- <div class="lg:col-span-2 bg-white dark:bg-zinc-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Pegawai per Unit Kerja</h3>
                
                    <div x-data="{
                        labels: @json($chartLabels),
                        data: @json($chartData),
                        init() {
                            // Jangan coba gambar grafik jika elemennya tidak terlihat di DOM
                            if (typeof Chart === 'undefined') {
                                console.error('Chart.js is not loaded.');
                                return;
                            }
                            new Chart(this.$refs.canvas, {
                                type: 'bar',
                                data: {
                                    labels: this.labels,
                                    datasets: [{
                                        label: 'Jumlah Pegawai',
                                        data: this.data,
                                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: { 
                                            beginAtZero: true, 
                                            ticks: { 
                                                precision: 0, 
                                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7281'
                                            },
                                            grid: { color: document.documentElement.classList.contains('dark') ? '#3f3f46' : '#e5e7eb' }
                                        },
                                        x: {
                                            ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7281' },
                                            grid: { display: false }
                                        }
                                    },
                                    plugins: { 
                                        legend: { display: false } 
                                    }
                                }
                            })
                        }
                    }" wire:ignore>
                        <div class="relative h-72">
                            <canvas x-ref="canvas"></canvas>
                        </div>
                    </div>
                </div> --}}

                {{-- Kolom Kanan: Aktivitas Cuti Terbaru --}}
                <div class="lg:col-span-3 bg-white dark:bg-zinc-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700">
                     <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Pengajuan Cuti Terbaru</h3>
                     <div class="space-y-4">
                        @forelse($cutiTerbaru as $cuti)
                            <div class="flex items-start gap-3">
                                <div class="bg-gray-100 dark:bg-zinc-700 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check text-gray-600 dark:text-gray-300"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cuti->pegawai->nama }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Jabatan: {{ $cuti->pegawai->jabatan->nama_jabatan }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Durasi: {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_akhir)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan cuti.</p>
                        @endforelse
                     </div>
                </div>

            </div>
        </div>
    </div>
</div>