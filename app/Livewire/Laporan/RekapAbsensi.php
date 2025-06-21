<?php

namespace App\Livewire\Laporan;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RekapAbsensi extends Component
{
    use WithPagination;

    public $bulan;
    public $tahun;
    public string $search = '';

    public $workdays = 0;
    public array $absensiData = [];
    public bool $isEditing = false;

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadAbsensiData();
    }

    public function updated($property)
    {
        if (in_array($property, ['bulan', 'tahun', 'search'])) {
            $this->resetPage();
            $this->loadAbsensiData();
        }
    }

    public function loadAbsensiData()
    {
        $startOfMonth = Carbon::create($this->tahun, $this->bulan, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $this->workdays = $startOfMonth->diffInWeekdays($endOfMonth);

        $pegawais = Pegawai::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', '%'.$this->search.'%')->orWhere('nip', 'like', '%'.$this->search.'%'))
            ->orderBy('nama')->get();

        $pegawaiIds = $pegawais->pluck('id');
        $existingAbsensi = Absensi::whereIn('pegawai_id', $pegawaiIds)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get()
            ->groupBy('pegawai_id');

        $existingCuti = Cuti::whereIn('pegawai_id', $pegawaiIds)
            ->where(fn($q) => $q->where('tanggal_mulai', '<=', $endOfMonth)->where('tanggal_akhir', '>=', $startOfMonth))
            ->get()
            ->groupBy('pegawai_id');

        $data = [];
        foreach ($pegawais as $pegawai) {
            $sakit = 0; $izin = 0; $alpha = 0; $cutiDays = 0;

            if (isset($existingAbsensi[$pegawai->id])) {
                $sakit = $existingAbsensi[$pegawai->id]->where('status', 'sakit')->count();
                $izin = $existingAbsensi[$pegawai->id]->where('status', 'izin')->count();
                $alpha = $existingAbsensi[$pegawai->id]->where('status', 'alpha')->count();
            }

            if (isset($existingCuti[$pegawai->id])) {
                foreach ($existingCuti[$pegawai->id] as $cuti) {
                    // Setel semua waktu ke awal hari (00:00:00) sebelum membandingkan
                    $tglMulaiCuti = Carbon::parse($cuti->tanggal_mulai)->startOfDay();
                    $tglAkhirCuti = Carbon::parse($cuti->tanggal_akhir)->startOfDay();
                    
                    // Bandingkan dengan tanggal bulan yang juga sudah dinormalisasi
                    $actualStart = $tglMulaiCuti->max($startOfMonth->copy()->startOfDay());
                    $actualEnd = $tglAkhirCuti->min($endOfMonth->copy()->startOfDay());
                    
                    // Perhitungan sekarang akan menghasilkan angka bulat (integer)
                    $cutiDays += $actualStart->diffInDays($actualEnd) + 1;
                }
            }

            $data[$pegawai->id] = [
                'nama' => $pegawai->nama, 'nip' => $pegawai->nip,
                'hari_kerja' => $this->workdays, // Menggunakan properti workdays
                'sakit' => $sakit, 'izin' => $izin, 'alpha' => $alpha, 'cuti' => $cutiDays,
            ];
        }
        $this->absensiData = $data;
    }

    public function toggleEditMode()
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->loadAbsensiData();
        }
    }
    
    public function save()
    {
        DB::transaction(function () {
            foreach ($this->absensiData as $pegawaiId => $data) {
                // 1. Hapus data lama
                Absensi::where('pegawai_id', $pegawaiId)
                    ->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->delete();

                // 2. Buat ulang record baru
                $this->createAbsensiRecords($pegawaiId, 'sakit', $data['sakit']);
                $this->createAbsensiRecords($pegawaiId, 'izin', $data['izin']);
                $this->createAbsensiRecords($pegawaiId, 'alpha', $data['alpha']);
            }
        });

        // 3. Tampilkan notifikasi dan kembalikan ke mode lihat
        session()->flash('success', 'Data absensi berhasil diperbarui.');
        $this->isEditing = false;
        $this->loadAbsensiData();
    }

    // Menghapus method deleteAbsensi() sesuai permintaan sebelumnya
    // public function deleteAbsensi($pegawaiId) { ... }

    private function createAbsensiRecords($pegawaiId, $status, $count)
    {
        if ($count <= 0) return;
        
        $dates = [];
        $currentDate = Carbon::create($this->tahun, $this->bulan, 1);
        $endDate = $currentDate->copy()->endOfMonth();

        for ($i = 0; $i < $count; $i++) {
            while ($currentDate->isWeekend()) {
                $currentDate->addDay();
            }
            if ($currentDate->gt($endDate)) break;

            $dates[] = [
                'pegawai_id' => $pegawaiId,
                'tanggal' => $currentDate->copy(),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $currentDate->addDay();
        }

        if (!empty($dates)) {
            Absensi::insert($dates);
        }
    }

    public function render()
    {
        return view('livewire.laporan.rekap-absensi');
    }
}