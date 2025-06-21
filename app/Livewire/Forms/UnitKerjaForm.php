<?php

namespace App\Livewire\Forms;

use App\Models\UnitKerja;
use Livewire\Attributes\Rule;
use Livewire\Form;

class UnitKerjaForm extends Form
{
    public ?UnitKerja $unitKerja;

    #[Rule('required|string|max:255')]
    public string $nama_unit = '';

    #[Rule('required|string|max:255')]
    public string $lokasi = '';

    public function setUnitKerja(UnitKerja $unitKerja)
    {
        $this->unitKerja = $unitKerja;
        $this->nama_unit = $unitKerja->nama_unit;
        $this->lokasi = $unitKerja->lokasi;
    }

    public function store()
    {
        $this->validate();
        UnitKerja::create($this->all());
    }

    public function update()
    {
        $this->validate();
        $this->unitKerja->update($this->all());
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->unitKerja = null;
    }
}