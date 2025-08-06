<?php

namespace App\Http\Controllers;

use App\Models\MedsosTeam;
use App\Events\ActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MedsosController extends Controller
{
    /**
     * API: Tampilkan semua data medsos (format JSON untuk Next.js)
     */
    public function json()
    {
        try {
            $teams = MedsosTeam::with('platforms')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data media sosial berhasil dimuat',
                'data' => $teams->map(function ($team) {
                    return [
                        'id' => $team->id,
                        'name' => $team->name,
                        'username' => ltrim($team->username, '@'),
                        'image' => $team->image_url,
                        'platforms' => $team->platforms->map(function ($p) {
                            return [
                                'name' => $p->name,
                                'url' => $p->url,
                            ];
                        }),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('API Medsos JSON Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data media sosial.'
            ], 500);
        }
    }

    /**
     * Tampilkan semua data (web)
     */
    public function index()
    {

        $medsos = MedsosTeam::with('platforms')->latest()->paginate(10);
        return view('medsos.index', compact('medsos'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        return view('medsos.create');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'platforms' => 'required|array|min:1',
            'platforms.*.name' => 'required|string|max:50',
            'platforms.*.url' => 'required|url|max:500',
        ]);

        DB::beginTransaction();
        try {
            $team = new MedsosTeam();
            $team->name = $validated['name'];
            $team->username = ltrim($validated['username'], '@');

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('medsos', 'public');
                $team->image = $path;
            }

            $team->save();

            // Simpan platforms
            foreach ($validated['platforms'] as $platform) {
                $team->platforms()->create([
                    'name' => $platform['name'],
                    'url' => $platform['url'],
                ]);
            }

            DB::commit();

            // 🔔 Kirim notifikasi: Ditambahkan oleh [User]
            event(new ActivityNotification([
                'title' => 'Media sosial baru: ' . $team->name,
                'type' => 'Social',
                'icon' => 'fa-hashtag',
                'bg' => 'primary',
                'user_id' => Auth::id(),
            ]));

            return redirect()->route('medsos.index')->with('success', 'Media sosial berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan medsos: ' . $e->getMessage(), [
                'input' => $request->except('image'),
            ]);

            return back()->withInput()->with('error', 'Gagal menyimpan data. Coba lagi.');
        }
    }

    /**
     * Tampilkan form edit
     */
    public function edit(MedsosTeam $medsos)
    {
        $medsos->load('platforms');
        return view('medsos.create', compact('medsos'));
    }

    /**
     * Update data
     */
    public function update(Request $request, MedsosTeam $medsos)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'platforms' => 'required|array|min:1',
            'platforms.*.name' => 'required|string|max:50',
            'platforms.*.url' => 'required|url|max:500',
        ]);

        DB::beginTransaction();
        try {
            $medsos->name = $validated['name'];
            $medsos->username = ltrim($validated['username'], '@');

            if ($request->hasFile('image')) {
                if ($medsos->image) {
                    Storage::disk('public')->delete($medsos->image);
                }
                $path = $request->file('image')->store('medsos', 'public');
                $medsos->image = $path;
            }

            $medsos->save();

            // Hapus dan simpan ulang platforms
            $medsos->platforms()->delete();

            foreach ($validated['platforms'] as $platform) {
                $medsos->platforms()->create([
                    'name' => $platform['name'],
                    'url' => $platform['url'],
                ]);
            }

            DB::commit();

            // 🔔 Kirim notifikasi: Diperbarui oleh [User]
            event(new ActivityNotification([
                'title' => 'Media sosial diperbarui: ' . $medsos->name,
                'type' => 'Social',
                'icon' => 'fa-hashtag',
                'bg' => 'warning',
                'user_id' => Auth::id(),
            ]));

            return redirect()->route('medsos.index')->with('success', 'Media sosial berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update medsos: ' . $e->getMessage(), [
                'input' => $request->except('image'),
                'medsos_id' => $medsos->id,
            ]);

            return back()->withInput()->with('error', 'Gagal memperbarui data. Coba lagi.');
        }
    }

    /**
     * Hapus data
     */
    public function destroy(MedsosTeam $medsos)
    {
        $nama = $medsos->name; // Simpan nama sebelum dihapus

        DB::beginTransaction();
        try {
            if ($medsos->image) {
                Storage::disk('public')->delete($medsos->image);
            }

            $medsos->delete();

            DB::commit();

            // 🔔 Kirim notifikasi: Dihapus oleh [User]
            event(new ActivityNotification([
                'title' => 'Media sosial dihapus: ' . $nama,
                'type' => 'Social',
                'icon' => 'fa-trash',
                'bg' => 'danger',
                'user_id' => Auth::id(),
            ]));

            return redirect()->route('medsos.index')->with('success', 'Media sosial berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus medsos: ' . $e->getMessage(), [
                'medsos_id' => $medsos->id,
            ]);

            return redirect()->route('medsos.index')->with('error', 'Gagal menghapus data.');
        }
    }
}
