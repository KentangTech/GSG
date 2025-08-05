<?php

namespace App\Http\Controllers;

use App\Models\Bisnis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BisnisController extends Controller
{
    /**
     * Tampilkan semua data bisnis
     */
    public function index()
    {
        $bisnis = Bisnis::latest()->paginate(10);
        return view('bisnis.index', compact('bisnis'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        return view('bisnis.create');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tag' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
        ]);

        $data = $request->only('judul', 'tag', 'deskripsi');

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('bisnis', 'public');
            $data['gambar'] = $path;
        }

        Bisnis::create($data);

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Bisnis $bisnis)
    {
        return view('bisnis.create', compact('bisnis'));
    }

    /**
     * Update data
     */
    public function update(Request $request, Bisnis $bisnis)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tag' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('judul', 'tag', 'deskripsi');

        if ($request->hasFile('gambar')) {
            if ($bisnis->gambar) {
                Storage::disk('public')->delete($bisnis->gambar);
            }
            $path = $request->file('gambar')->store('bisnis', 'public');
            $data['gambar'] = $path;
        }

        $bisnis->update($data);

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy(Bisnis $bisnis)
    {
        if ($bisnis->gambar) {
            Storage::disk('public')->delete($bisnis->gambar);
        }

        $bisnis->delete();

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil dihapus.');
    }

    /**
     * API: Kembalikan data bisnis dalam format JSON
     */
    public function json()
    {
        $bisnis = Bisnis::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Bisnis berhasil dimuat',
            'data' => $bisnis->map(function ($data) {
                return [
                    'id' => $data->id,
                    'judul' => $data->judul,
                    'tag' => $data->tag,
                    'deskripsi' => $data->deskripsi,
                    'gambar_url' => $data->gambar ? asset('storage/' . $data->gambar) : null,
                ];
            }),
        ]);
    }
}
