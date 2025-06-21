<?php
namespace App\Livewire\Jabatan;

use App\Livewire\Forms\JabatanForm;
use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public JabatanForm $form;
    public bool $showModal = false;
    public string $search = '';

    public function create()
    {
        $this->form->reset();
        $this->showModal = true;
    }

    public function save()
    {
        $this->form->store();
        $this->showModal = false;
        session()->flash('success', 'Jabatan berhasil disimpan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $this->form->setJabatan($jabatan);
        $this->showModal = true;
    }

    public function delete(Jabatan $jabatan)
    {
        $jabatan->delete();
        session()->flash('success', 'Jabatan berhasil dihapus.');
    }

    public function render()
    {
        $jabatans = Jabatan::where('nama_jabatan', 'like', '%'.$this->search.'%')
                            ->paginate(10);
        return view('livewire.jabatan.index', compact('jabatans'));
    }
}