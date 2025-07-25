<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::latest();

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('content', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        $news = $query->paginate(9);
        $categories = News::distinct()->pluck('category')->filter();
        return view('news.index', compact('news', 'categories'));
    }

    public function json()
    {
        $news = News::latest()->get();
        return response()->json($news);
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category']
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), $validated['title']);
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image_base64' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category']
        ];

        if ($request->filled('image_base64')) {
            if ($news->image && Storage::exists('public/' . $news->image)) {
                Storage::delete('public/' . $news->image);
            }

            $data['image'] = $this->storeBase64Image($request->input('image_base64'), $validated['title']);
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->image && Storage::exists('public/' . $news->image)) {
            Storage::delete('public/' . $news->image);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function storeImage($image, $title)
    {
        $extension = $image->getClientOriginalExtension();
        $slug = Str::slug($title);
        $fileName = "{$slug}-" . time() . '.' . $extension;

        return $image->storeAs('news', $fileName, 'public');
    }

    private function storeBase64Image($base64Image, $title)
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageName = Str::slug($title) . '-' . time() . '.jpg';

        Storage::disk('public')->put("news/{$imageName}", base64_decode($image));

        return "news/{$imageName}";
    }
}
