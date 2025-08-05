<?php

namespace App\Http\Controllers;

use App\Models\Direksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DireksiController extends Controller
{
    const MAX_DIREKSI = 3;

    /**
     * Cek apakah jumlah direksi sudah mencapai batas
     */
    private function isLimitReached()
    {
        return Direksi::count() >= self::MAX_DIREKSI;
    }

    /**
     * Tampilkan semua data direksi
     */
    public function index()
    {
        $direksis = Direksi::latest()->paginate(10);
        $limitReached = $this->isLimitReached();

        return view('direksi.index', compact('direksis', 'limitReached'));
    }

    /**
     * Tampilkan form tambah (dengan cek batas)
     */
    public function create()
    {
        if ($this->isLimitReached()) {
            return redirect()
                ->route('direksi.index')
                ->with('error', 'Tidak dapat menambahkan lebih dari ' . self::MAX_DIREKSI . ' direksi.');
        }

        return view('direksi.create');
    }

    /**
     * Simpan data baru (dengan cek batas)
     */
    public function store(Request $request)
    {
        if ($this->isLimitReached()) {
            return redirect()
                ->route('direksi.index')
                ->with('error', 'Gagal menyimpan: Jumlah direksi sudah mencapai batas maksimal (' . self::MAX_DIREKSI . ').');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('nama', 'posisi');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('direksi', 'public');
            $data['foto'] = $path;
        }

        Direksi::create($data);

        return redirect()->route('direksi.index')->with('success', 'Direksi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Direksi $direksi)
    {
        return view('direksi.create', compact('direksi'));
    }

    /**
     * Update data
     */
    public function update(Request $request, Direksi $direksi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('nama', 'posisi');

        if ($request->hasFile('foto')) {
            if ($direksi->foto) {
                Storage::disk('public')->delete($direksi->foto);
            }
            $path = $request->file('foto')->store('direksi', 'public');
            $data['foto'] = $path;
        }

        $direksi->update($data);

        return redirect()->route('direksi.index')->with('success', 'Direksi berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy(Direksi $direksi)
    {
        if ($direksi->foto) {
            Storage::disk('public')->delete($direksi->foto);
        }

        $direksi->delete();

        return redirect()->route('direksi.index')->with('success', 'Direksi berhasil dihapus.');
    }

    public function json()
    {
        $direksis = Direksi::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data direksi berhasil dimuat',
            'data' => $direksis->map(function ($d) {
                return [
                    'id' => $d->id,
                    'nama' => $d->nama,
                    'posisi' => $d->posisi,
                    'foto_url' => $d->foto ? asset('storage/' . $d->foto) : null,
                ];
            }),
        ]);
    }
}
