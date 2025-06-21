<?php

namespace App\Livewire\UnitKerja;

use App\Livewire\Forms\UnitKerjaForm;
use App\Models\UnitKerja;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public UnitKerjaForm $form;
    public bool $showModal = false;
    public string $search = '';

    public function create()
    {
        $this->form->reset();
        $this->showModal = true;
    }

    public function save()
    {
        if (isset($this->form->unitKerja)) {
            $this->form->update();
            session()->flash('success', 'Unit Kerja berhasil diperbarui.');
        } else {
            $this->form->store();
            session()->flash('success', 'Unit Kerja berhasil ditambahkan.');
        }
        $this->showModal = false;
    }

    public function edit(UnitKerja $unitKerja)
    {
        $this->form->setUnitKerja($unitKerja);
        $this->showModal = true;
    }

    public function delete(UnitKerja $unitKerja)
    {
        $unitKerja->delete();
        session()->flash('success', 'Unit Kerja berhasil dihapus.');
    }

    public function render()
    {
        $unitKerjas = UnitKerja::where('nama_unit', 'like', '%' . $this->search . '%')
            ->orWhere('lokasi', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.unit-kerja.index', compact('unitKerjas'));
    }
}