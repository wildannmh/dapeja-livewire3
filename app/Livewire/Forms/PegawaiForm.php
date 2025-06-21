<?php

namespace App\Livewire\Forms;

use App\Models\Pegawai;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PegawaiForm extends Form
{
    public ?Pegawai $pegawai = null;

    public string $nip = '';
    public string $nama = '';
    public $jabatan_id = '';
    public $unit_kerja_id = '';
    public $gaji = '';

    public function rules()
    {
        return [
            // Rule::unique akan mengabaikan NIP dari pegawai yang sedang diedit
            'nip' => ['required', 'string', Rule::unique('pegawais')->ignore($this->pegawai)],
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'gaji' => 'required|numeric|min:0',
        ];
    }

    public function setPegawai(Pegawai $pegawai)
    {
        $this->pegawai = $pegawai;
        $this->nip = $pegawai->nip;
        $this->nama = $pegawai->nama;
        $this->jabatan_id = $pegawai->jabatan_id;
        $this->unit_kerja_id = $pegawai->unit_kerja_id;
        $this->gaji = $pegawai->gaji;
    }

    public function store()
    {
        $validated = $this->validate();
        Pegawai::create($validated);
    }

    public function update()
    {
        $validated = $this->validate();
        $this->pegawai->update($validated);
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->pegawai = null;
    }
}