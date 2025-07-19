<?php

namespace App\Http\Controllers;

use App\Models\Mindmap;
use App\Models\Ringkasan;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MindmapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function brace()
    {
        return view('mindmap.brace');
    }

    public function bubble()
    {
        return view('mindmap.bubble');
    }

    public function flow()
    {
        return view('mindmap.flow');
    }

    public function multi()
    {
        return view('mindmap.multi');
    }

    public function spider()
    {
        return view('mindmap.spider');
    }

    public function custom()
    {
        return view('mindmap.custom');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function generateSummary(Request $request)
    {
        $mindmap = $request->input('mindmap');

        if (empty($mindmap) || !is_array($mindmap)) {
            return response()->json(['error' => 'Mindmap kosong atau tidak valid'], 400);
        }

        if (count($mindmap) > 500) {
            return response()->json(['error' => 'Mindmap terlalu besar untuk diproses. Maksimal 500 node.'], 413);
        }

        $mindmapJson = json_encode($mindmap, JSON_PRETTY_PRINT);

        $prompt = <<<EOT
        Anda adalah asisten AI yang ahli dalam merangkum konten dari mindmap.
        Berikut adalah data mindmap dalam bentuk JSON. Tolong baca struktur dan buatkan ringkasan singkat dan informatif yang menjelaskan topik utama, subtopik, dan keterkaitan antar ide.
        Jangan gunakan simbol markdown seperti bintang (*), tanda pagar (#), atau format penekanan teks lainnya. Gunakan teks biasa saja.
        Format ringkasan (teks biasa, tanpa format khusus):
            Topik utama: ...
        Subtopik: ...
        Ringkasan umum: ...
        Data mindmap:
            ```json
        {$mindmapJson}
        EOT;
        try {
            $model = Gemini::generativeModel(model: 'gemini-2.0-flash');

            Log::info("Sending prompt to Gemini", ['length' => strlen($prompt)]);

            $response = $model->generateContent($prompt);
            $summary = $response->text();

            return response()->json(['summary' => $summary]);
        } catch (\Throwable $e) {
            Log::error('Gagal memanggil Gemini API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Gagal menghasilkan ringkasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:brace,bubble,flow,multi,spider',
            'mindmap' => 'required|array|min:1',
            'mindmap.*.node' => 'required|string',
            'mindmap.*.parent_node' => 'nullable|string',
            'image' => 'nullable|string' // base64 image
        ]);

        $userId = Auth::id();
        $imagePath = null;

        if ($request->has('image')) {
            try {
                // Strip data:image/png;base64,...
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
                $imageData = base64_decode($base64Image);

                $fileName = 'mindmap_' . time() . '_' . uniqid() . '.png';
                $filePath = storage_path('app/public/mindmaps/' . $fileName);

                // Ensure directory exists
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0755, true);
                }

                file_put_contents($filePath, $imageData);
                $imagePath = 'mindmaps/' . $fileName;
            } catch (\Exception $e) {
                return response()->json(['message' => 'Gagal menyimpan gambar', 'error' => $e->getMessage()], 500);
            }
        }

        foreach ($request->mindmap as $item) {
            DB::table('mindmaps')->insert([
                'user' => $userId,
                'title' => $request->title,
                'node' => $item['node'],
                'parent_node' => $item['parent_node'],
                'type' => $request->type,
                'gambar_mindmap' => $imagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Mindmap berhasil disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function saved()
    {
        $query = Mindmap::with('userRelation')->orderBy('created_at', 'desc');

        if (Auth::user()->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('user', Auth::id())
                    ->orWhere('shareable', 'yes');
            });
        }

        $mindmaps = $query->get();

        $ringkasanIds = DB::table('ringkasans')
            ->where('user', Auth::id())
            ->pluck('mindmaps')
            ->toArray();

        return view('mindmap', compact('mindmaps', 'ringkasanIds'));
    }

    public function getRingkasan($id)
    {
        $mindmap = Mindmap::with('userRelation')->findOrFail($id);

        $ringkasanPribadi = null;
        if ($mindmap->user === Auth::id()) {
            $ringkasanPribadi = $mindmap->ringkasan_pribadi;
        }

        $ringkasans = Ringkasan::where('mindmaps', $id)
            ->with('userRelation')
            ->get()
            ->map(function ($r) {
                return [
                    'user' => $r->userRelation->name ?? 'Tidak diketahui',
                    'ringkasan' => $r->ringkasan,
                ];
            });

        return response()->json([
            'mindmap' => [
                'title' => $mindmap->title,
                'type' => $mindmap->type,
                'creator' => $mindmap->userRelation->name ?? 'Tidak diketahui',
                'created_at' => $mindmap->created_at->format('d M Y H:i'),
            ],
            'ringkasan_pribadi' => $ringkasanPribadi,
            'ringkasan_lain' => $ringkasans,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function toggleShare($id)
    {
        $mindmap = Mindmap::findOrFail($id);

        if ($mindmap->user !== Auth::id()) {
            return response()->json(['error' => 'Anda tidak diizinkan mengubah status bagikan.'], 403);
        }

        $mindmap->shareable = $mindmap->shareable === 'yes' ? 'no' : 'yes';
        $mindmap->save();

        return response()->json([
            'message' => 'Status berbagi berhasil diperbarui.',
            'shareable' => $mindmap->shareable,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function simpanRingkasan(Request $request, $id)
    {
        $request->validate([
            'ringkasan' => 'required|string',
        ]);

        $mindmap = Mindmap::findOrFail($id);

        // Pastikan pengguna hanya bisa menyimpan ringkasan untuk mindmap mereka sendiri
        if ($mindmap->user !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menyimpan ringkasan ini.'
            ], 403);
        }

        try {
            // Simpan ringkasan (update jika sudah ada, atau buat baru)
            Ringkasan::updateOrCreate(
                [
                    'user' => Auth::id(),
                    'mindmaps' => $mindmap->id
                ],
                [
                    'ringkasan' => $request->ringkasan,
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Ringkasan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ringkasan. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mindmap = Mindmap::findOrFail($id);

        if ($mindmap->user !== Auth::id()) {
            return redirect()->back()->with('toast', [
                'message' => 'Anda tidak memiliki akses untuk menghapus mindmap ini.',
                'type' => 'error'
            ]);
        }

        try {
            DB::table('mindmaps')
                ->where('id', $id)
                ->delete();

            return redirect()->back()->with('toast', [
                'message' => 'Mindmap berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus mindmap', ['error' => $e->getMessage()]);

            return redirect()->back()->with('toast', [
                'message' => 'Gagal menghapus mindmap. Silakan coba lagi.',
                'type' => 'error'
            ]);
        }
    }
}
