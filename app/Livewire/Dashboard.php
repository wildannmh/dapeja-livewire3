<?php

namespace App\Livewire;

use App\Models\Cuti;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Data untuk Kartu Statistik Utama
        $jumlahPegawai = Pegawai::count();
        $jumlahUnitKerja = UnitKerja::count();
        $jumlahJabatan = Jabatan::count();

        // 2. Data untuk Grafik (Jumlah Pegawai per Unit Kerja)
        $pegawaiPerUnit = UnitKerja::withCount('pegawais')->orderBy('pegawais_count', 'desc')->get();
        // Memformat data agar bisa dibaca oleh Chart.js
        $chartLabels = $pegawaiPerUnit->pluck('nama_unit');
        $chartData = $pegawaiPerUnit->pluck('pegawais_count');

        // 3. Data untuk Aktivitas Terbaru (5 pengajuan cuti terakhir)
        $cutiTerbaru = Cuti::with('pegawai.jabatan')->latest()->take(5)->get();

        return view('dashboard', [
            'jumlahPegawai' => $jumlahPegawai,
            'jumlahUnitKerja' => $jumlahUnitKerja,
            'jumlahJabatan' => $jumlahJabatan,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'cutiTerbaru' => $cutiTerbaru,
        ]);
    }
}