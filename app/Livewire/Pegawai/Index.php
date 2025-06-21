<?php

namespace App\Livewire\Pegawai;

use App\Livewire\Forms\PegawaiForm;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public PegawaiForm $form;
    public bool $showModal = false;
    public string $search = '';

    public function create()
    {
        $this->form->reset();
        $this->showModal = true;
    }

    public function save()
    {
        if (isset($this->form->pegawai)) {
            $this->form->update();
            session()->flash('success', 'Data pegawai berhasil diperbarui.');
        } else {
            $this->form->store();
            session()->flash('success', 'Data pegawai berhasil ditambahkan.');
        }
        $this->showModal = false;
    }

    public function edit(Pegawai $pegawai)
    {
        $this->form->setPegawai($pegawai);
        $this->showModal = true;
    }

    public function delete(Pegawai $pegawai)
    {
        $pegawai->delete();
        session()->flash('success', 'Data pegawai berhasil dihapus.');
    }

    public function render()
    {
        $pegawais = Pegawai::with(['jabatan', 'unitKerja'])
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->paginate(10);
            
        // Ambil data untuk dropdown di form modal
        $jabatans = Jabatan::all();
        $unitKerjas = UnitKerja::all();

        return view('livewire.pegawai.index', compact('pegawais', 'jabatans', 'unitKerjas'));
    }
}