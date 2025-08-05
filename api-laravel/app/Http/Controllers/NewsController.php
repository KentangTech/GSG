<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Tampilkan semua berita
     */
    public function index()
    {
        $news = News::with('uploader')->latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Simpan berita baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('judul', 'deskripsi');
        $data['update_time'] = now();
        $data['uploaded_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('news', 'public');
            $data['gambar'] = $path;
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(News $news)
    {
        return view('news.create', compact('news'));
    }

    /**
     * Update berita
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $data = $request->only('judul', 'deskripsi');

        if ($request->hasFile('gambar')) {
            if ($news->gambar) {
                Storage::disk('public')->delete($news->gambar);
            }
            $path = $request->file('gambar')->store('news', 'public');
            $data['gambar'] = $path;
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita
     */
    public function destroy(News $news)
    {
        if ($news->gambar) {
            Storage::disk('public')->delete($news->gambar);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * API: Kembalikan data berita dalam format JSON
     */
    public function json()
    {
        $news = News::with('uploader')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Berita berhasil dimuat',
            'data' => $news->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'update_time' => $item->update_time,
                    'uploaded_by' => $item->uploader ? $item->uploader->name : 'Unknown',
                ];
            }),
        ]);
    }
}

