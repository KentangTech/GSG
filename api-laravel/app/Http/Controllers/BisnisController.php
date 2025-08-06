<?php

namespace App\Http\Controllers;

use App\Models\Bisnis;
use App\Events\ActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('judul', 'tag', 'deskripsi');

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('bisnis', 'public');
            $data['gambar'] = $path;
        }

        $bisnis = Bisnis::create($data);

        // Kirim notifikasi real-time
        event(new ActivityNotification([
            'title' => 'Bisnis baru: ' . $bisnis->judul,
            'type' => 'Bisnis',
            'icon' => 'fa-store',
            'bg' => 'primary',
            'user_id' => Auth::id(),
        ]));

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
            // Hapus gambar lama
            if ($bisnis->gambar) {
                Storage::disk('public')->delete($bisnis->gambar);
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('bisnis', 'public');
            $data['gambar'] = $path;
        }

        $bisnis->update($data);

        // Kirim notifikasi: diperbarui
        event(new ActivityNotification([
            'title' => 'Bisnis diperbarui: ' . $bisnis->judul,
            'type' => 'Bisnis',
            'icon' => 'fa-store',
            'bg' => 'warning',
            'user_id' => Auth::id(),
        ]));

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy(Bisnis $bisnis)
    {
        $judul = $bisnis->judul; // Simpan untuk notifikasi

        // Hapus gambar jika ada
        if ($bisnis->gambar) {
            Storage::disk('public')->delete($bisnis->gambar);
        }

        $bisnis->delete();

        // Kirim notifikasi: dihapus
        event(new ActivityNotification([
            'title' => 'Bisnis dihapus: ' . $judul,
            'type' => 'Bisnis',
            'icon' => 'fa-trash',
            'bg' => 'danger',
            'user_id' => Auth::id(),
        ]));

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
            'data' => $bisnis->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'tag' => $item->tag,
                    'deskripsi' => $item->deskripsi,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                ];
            }),
        ]);
    }
}
