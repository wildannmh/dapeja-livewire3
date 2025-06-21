<?php
namespace App\Livewire\Forms;

use App\Models\Jabatan;
use Livewire\Attributes\Rule;
use Livewire\Form;

class JabatanForm extends Form
{
    public ?Jabatan $jabatan;

    #[Rule('required|string|max:255')]
    public string $nama_jabatan = '';

    #[Rule('required|numeric')]
    public string $tunjangan = '';

    public function setJabatan(Jabatan $jabatan)
    {
        $this->jabatan = $jabatan;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->tunjangan = $jabatan->tunjangan;
    }

    public function store()
    {
        $this->validate();
        Jabatan::create($this->all());
    }

    public function update()
    {
        $this->validate();
        $this->jabatan->update($this->all());
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->jabatan = null;
    }
}