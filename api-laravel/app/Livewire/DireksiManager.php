<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Direksi;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DireksiManager extends Component
{
    use WithFileUploads;

    public $nama;
    public $posisi;
    public $gambar;
    public $edit_id;
    public $edit_nama;
    public $edit_posisi;
    public $edit_gambar;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'posisi' => 'required|in:Direktur Utama,Direktur Operasional,Direktur Keuangan',
        'gambar' => 'nullable|image|max:2048',
    ];

    protected $editRules = [
        'edit_nama' => 'required|string|max:255',
        'edit_posisi' => 'required|in:Direktur Utama,Direktur Operasional,Direktur Keuangan',
        'edit_gambar' => 'nullable|image|max:2048',
    ];

    public function render()
    {
        $direksi = Direksi::all();
        $limitReached = $direksi->count() >= 3;

        return view('livewire.direksi-manager', compact('direksi', 'limitReached'));
    }

    public function store()
    {
        $direksiCount = Direksi::count();
        if ($direksiCount >= 3) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Maksimal 3 direktur sudah tercapai.'
            ]);
            return;
        }

        $this->validate();

        $data = ['nama' => $this->nama, 'posisi' => $this->posisi];

        if ($this->gambar) {
            $filename = time() . '_' . $this->gambar->getClientOriginalName();
            $this->gambar->storeAs('public/direksi', $filename);
            $data['gambar'] = $filename;
        }

        if (!Storage::exists('public/direksi/' . $filename)) {
            session()->flash('message', 'Upload gagal: File tidak tersimpan');
            return;
        }

        Direksi::create($data);
        $this->resetForm();
        $this->dispatch('direksi-updated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Direktur berhasil ditambahkan.'
        ]);
    }

    public function edit($id)
    {
        $direksi = Direksi::findOrFail($id);
        $this->edit_id = $direksi->id;
        $this->edit_nama = $direksi->nama;
        $this->edit_posisi = $direksi->posisi;
        $this->edit_gambar = null;
    }

    public function update()
    {
        $this->validate($this->editRules);

        $direksi = Direksi::findOrFail($this->edit_id);
        $data = ['nama' => $this->edit_nama, 'posisi' => $this->edit_posisi];

        if ($this->edit_gambar) {
            if ($direksi->gambar) {
                Storage::delete('public/direksi/' . $direksi->gambar);
            }
            $filename = time() . '_' . $this->edit_gambar->getClientOriginalName();
            $this->edit_gambar->storeAs('public/direksi', $filename);
            $data['gambar'] = $filename;
        }

        $direksi->update($data);
        $this->resetEdit();
        $this->dispatch('direksi-updated');
        $this->dispatch('close-modal');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Direktur berhasil diupdate.'
        ]);
    }

    public function delete($id)
    {
        $direksi = Direksi::findOrFail($id);
        if ($direksi->gambar) {
            Storage::delete('public/direksi/' . $direksi->gambar);
        }
        $direksi->delete();
        $this->dispatch('direksi-updated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Direktur berhasil dihapus.'
        ]);
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->posisi = '';
        $this->gambar = null;
    }

    private function resetEdit()
    {
        $this->edit_id = null;
        $this->edit_nama = '';
        $this->edit_posisi = '';
        $this->edit_gambar = null;
    }
}
