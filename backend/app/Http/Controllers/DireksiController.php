<?php

namespace App\Http\Controllers;

use App\Models\Direksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DireksiController extends Controller
{
    public function index()
    {
        $direksi = Direksi::all();
        return view('direksi.index', compact('direksi'));
    }

    public function json()
    {
        $direksi = Direksi::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'position' => $item->position,
                'image' => $item->image ? asset('storage/' . $item->image) : null,
            ];
        });

        return response()->json($direksi);
    }

    public function edit(Direksi $direksi)
    {
        return view('direksi.edit', compact('direksi'));
    }

    public function update(Request $request, Direksi $direksi)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'position' => trim($validated['position']),
            'name' => trim($validated['name']),
        ];

        if ($request->hasFile('image')) {
            if ($direksi->image && Storage::disk('public')->exists($direksi->image)) {
                Storage::disk('public')->delete($direksi->image);
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = str_replace(' ', '_', $validated['name']) . '.' . $extension;

            $data['image'] = $request->file('image')->storeAs('direksi', $fileName, 'public');
        }

        $direksi->update($data);

        return redirect()->route('direksi.index')->with('success', 'Direktur berhasil diperbarui.');
    }
}
