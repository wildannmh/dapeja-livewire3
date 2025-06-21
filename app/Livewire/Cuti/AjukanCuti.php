<?php

namespace App\Livewire\Cuti;

use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AjukanCuti extends Component
{
    // Aturan validasi untuk setiap field form
    #[Rule('required|exists:pegawais,id')]
    public $pegawai_id;

    #[Rule('required|date')]
    public $tanggal_mulai;

    #[Rule('required|date|after_or_equal:tanggal_mulai')]
    public $tanggal_akhir;

    #[Rule('required|string|min:10')]
    public $alasan;

    // Properti untuk menampilkan sisa cuti pegawai yang dipilih
    public $sisaCuti = null; // Awalnya null, akan diisi setelah admin memilih pegawai
    
    // Properti BARU untuk menyimpan tahun yang dipilih
    public $tahunCuti;

    /**
     * Method mount() berjalan sekali saat komponen pertama kali dimuat.
     * Kita gunakan untuk menginisialisasi tahun ke tahun saat ini.
     */
    public function mount()
    {
        $this->tahunCuti = now()->year;
    }

    /**
     * Lifecycle Hook yang berjalan saat properti $pegawai_id diperbarui.
     */
    public function updatedPegawaiId($pegawaiId)
    {
        if ($pegawaiId) {
            // Cek jika tanggal_mulai sudah diisi, gunakan tahunnya. Jika belum, gunakan tahun ini.
            $tahun = $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai)->year : now()->year;
            $this->hitungSisaCuti($pegawaiId, $tahun);
        } else {
            $this->sisaCuti = null;
        }
    }

    /**
     * Lifecycle Hook BARU yang berjalan saat properti $tahunCuti diperbarui.
     */
    // Tambahkan method baru ini
    public function updatedTanggalMulai($value)
    {
        // Pastikan ada tanggal mulai dan pegawai yang sudah dipilih
        if ($value && $this->pegawai_id) {
            // Ambil tahun dari input tanggal_mulai
            $tahun = Carbon::parse($value)->year;
            // Hitung sisa cuti untuk pegawai dan tahun tersebut
            $this->hitungSisaCuti($this->pegawai_id, $tahun);
        }
    }
    
    /**
     * Method hitungSisaCuti() sekarang menggunakan $this->tahunCuti.
     */
    public function hitungSisaCuti($pegawaiId, $tahun)
    {
        $cutiTerpakai = Cuti::where('pegawai_id', $pegawaiId)
            // Gunakan parameter $tahun, bukan now()->year
            ->whereYear('tanggal_mulai', $tahun)
            ->get()
            ->sum(function ($cuti) {
                return Carbon::parse($cuti->tanggal_mulai)->diffInDays(Carbon::parse($cuti->tanggal_akhir)) + 1;
            });

        $this->sisaCuti = 12 - $cutiTerpakai;
    }

    /**
     * Method ini dieksekusi saat form di-submit.
     */
    public function save()
    {
        $this->validate();

        // Ambil tahun dari tanggal_mulai untuk validasi akhir
        $tahun = Carbon::parse($this->tanggal_mulai)->year;
        $this->hitungSisaCuti($this->pegawai_id, $tahun);

        $durasiCutiBaru = Carbon::parse($this->tanggal_mulai)->diffInDays(Carbon::parse($this->tanggal_akhir)) + 1;

        if ($durasiCutiBaru > $this->sisaCuti) {
            // Buat pesan error lebih dinamis
            $this->addError('tanggal_akhir', 'Jatah cuti pegawai ini untuk tahun ' . $tahun . ' tidak mencukupi. Sisa jatahnya ' . $this->sisaCuti . ' hari.');
            return;
        }

        Cuti::create([
            'pegawai_id' => $this->pegawai_id,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_akhir' => $this->tanggal_akhir,
            'alasan' => $this->alasan,
        ]);

        session()->flash('success', 'Data cuti untuk pegawai berhasil ditambahkan.');
        
        $this->reset(['pegawai_id', 'tanggal_mulai', 'tanggal_akhir', 'alasan', 'sisaCuti']);
    }

    /**
     * Method render() bertanggung jawab untuk menampilkan view.
     */
    public function render()
    {
        // Ambil semua data pegawai untuk ditampilkan di dropdown form
        $pegawais = Pegawai::orderBy('nama')->get();
        
        return view('livewire.cuti.ajukan-cuti', [
            'pegawais' => $pegawais,
        ]);
    }
}