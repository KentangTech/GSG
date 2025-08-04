<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Direksi;
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
        'gambar' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
    ];

    protected $editRules = [
        'edit_nama' => 'required|string|max:255',
        'edit_posisi' => 'required|in:Direktur Utama,Direktur Operasional,Direktur Keuangan',
        'edit_gambar' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
    ];

    public function render()
    {
        $direksi = Direksi::all();
        $limitReached = $direksi->count() >= 3;

        return view('livewire.direksi-manager', compact('direksi', 'limitReached'));
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-create-modal');
    }

    public function edit($id)
    {
        $direksi = Direksi::findOrFail($id);
        $this->edit_id = $direksi->id;
        $this->edit_nama = $direksi->nama;
        $this->edit_posisi = $direksi->posisi;
        $this->edit_gambar = null;

        $this->dispatch('open-edit-modal');
    }

    public function store()
    {
        if (Direksi::count() >= 3) {
            session()->flash('message', 'Maksimal 3 direktur sudah tercapai.');
            $this->dispatch('closeModal');
            return;
        }

        $this->validate();

        $data = [
            'nama' => $this->nama,
            'posisi' => $this->posisi,
        ];

        if ($this->gambar) {
            $extension = $this->gambar->getClientOriginalExtension();
            $filename = 'direksi_' . time() . '_' . uniqid() . '.' . $extension;
            $this->gambar->storeAs('public/direksi', $filename);
            $data['gambar'] = $filename;
        }

        Direksi::create($data);

        $this->resetForm();
        $this->dispatch('closeModal');
        session()->flash('message', 'Direktur berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate($this->editRules);

        $direksi = Direksi::findOrFail($this->edit_id);
        $data = [
            'nama' => $this->edit_nama,
            'posisi' => $this->edit_posisi,
        ];

        if ($this->edit_gambar) {
            if ($direksi->gambar && Storage::exists('public/direksi/' . $direksi->gambar)) {
                Storage::delete('public/direksi/' . $direksi->gambar);
            }

            $extension = $this->edit_gambar->getClientOriginalExtension();
            $filename = 'direksi_' . time() . '_' . uniqid() . '.' . $extension;
            $this->edit_gambar->storeAs('public/direksi', $filename);
            $data['gambar'] = $filename;
        }

        $direksi->update($data);

        $this->resetEdit();
        $this->dispatch('closeModal');
        session()->flash('message', 'Direktur berhasil diperbarui.');
    }

    public function delete($id)
    {
        $direksi = Direksi::findOrFail($id);

        if ($direksi->gambar && Storage::exists('public/direksi/' . $direksi->gambar)) {
            Storage::delete('public/direksi/' . $direksi->gambar);
        }

        $direksi->delete();

        session()->flash('message', 'Direktur berhasil dihapus.');
        $this->dispatch('direksi-updated');
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
