<?php
namespace App\Livewire\Cuti;

use App\Models\Cuti; // Pastikan model Cuti di-import
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatCuti extends Component
{
    use WithPagination;
    
    public string $search = '';

    /**
     * Method BARU untuk menghapus data cuti.
     * Menerima instance model Cuti berkat Route Model Binding Livewire.
     */
    public function deleteCuti(Cuti $cuti)
    {
        // Hapus data dari database
        $cuti->delete();

        // Kirim pesan sukses ke session
        session()->flash('success', 'Data cuti berhasil dihapus.');

        // Reset paginasi agar tidak error jika menghapus data terakhir di halaman terakhir
        $this->resetPage();
    }

    public function render()
    {
        // Query untuk mencari data cuti berdasarkan nama pegawai
        $riwayat = Cuti::with('pegawai') // Eager load relasi pegawai
            ->whereHas('pegawai', function ($query) {
                // Terapkan filter hanya jika ada input pencarian
                $query->where('nama', 'like', '%'.$this->search.'%');
            })
            ->latest('tanggal_mulai') // Urutkan berdasarkan data terbaru
            ->paginate(10); // Batasi 10 data per halaman
            
        return view('livewire.cuti.riwayat-cuti', compact('riwayat'));
    }
}