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
    public function generateSummary($id)
    {
        $mindmapData = Mindmap::where('id', $id)->first();

        if (!$mindmapData) {
            return response()->json(['error' => 'Mindmap tidak ditemukan'], 404);
        }

        $nodes = Mindmap::where('title', $mindmapData->title)
            ->where('type', $mindmapData->type)
            ->where('user', $mindmapData->user)
            ->get(['node', 'parent_node'])
            ->toArray();

        if (empty($nodes)) {
            return response()->json(['error' => 'Data mindmap kosong'], 400);
        }

        if (count($nodes) > 500) {
            return response()->json(['error' => 'Mindmap terlalu besar (maks 500 node)'], 413);
        }

        $mindmapJson = json_encode($nodes, JSON_PRETTY_PRINT);

        $prompt = <<<EOT
Anda adalah asisten AI yang ahli dalam merangkum konten dari mindmap.
Berikut adalah data mindmap dalam bentuk JSON. Tolong baca struktur dan buatkan ringkasan singkat dan informatif yang menjelaskan topik utama, subtopik, dan keterkaitan antar ide.
Jangan gunakan simbol markdown atau format khusus.
Format ringkasan:
Topik utama: ...
Subtopik: ...
Ringkasan umum: ...
Data mindmap:
```json
{$mindmapJson}
EOT;

        try {
            $model = Gemini::generativeModel('gemini-2.0-flash');
            $response = $model->generateContent($prompt);
            $summary = $response->text();

            return response()->json(['summary' => $summary]);
        } catch (\Throwable $e) {
            Log::error('Gagal generate summary', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Gagal menghasilkan ringkasan'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'type' => 'required|in:brace,bubble,flow,multi,spider,custom',
            'mindmap' => 'required|array|min:1',
            'mindmap.*.node' => 'required|string',
            'mindmap.*.parent_node' => 'nullable|string',
            'image' => 'nullable|string' // base64 image
        ]);

        $userId = Auth::user()->id;
        $imagePath = null;

        if ($request->has('image')) {
            try {
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
                $imageData = base64_decode($base64Image);

                $fileName = 'mindmap_' . time() . '_' . uniqid() . '.png';
                $filePath = storage_path('app/public/mindmaps/' . $fileName);

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
                'ringkasan_pribadi' => $request->summary, // ✅ Simpan ringkasan pribadi
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
                $q->where('user', Auth::user()->id)
                    ->orWhere('shareable', 'yes');
            });
        }

        $mindmaps = $query->get()
            ->unique(function ($item) {
                return $item->title . '|' . $item->type;
            })
            ->values(); // reset key

        $ringkasanIds = DB::table('ringkasans')
            ->where('user', Auth::user()->id)
            ->pluck('mindmaps')
            ->toArray();

        return view('mindmap', compact('mindmaps', 'ringkasanIds'));
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:500',
        ]);

        $mindmap = Mindmap::findOrFail($id);

        DB::table('komentar')->insert([
            'user' => Auth::user()->id,
            'mindmaps' => $id,
            'komentar' => $request->komentar,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Komentar berhasil ditambahkan.']);
    }

    public function getRingkasan($id)
    {
        $mindmap = Mindmap::with('userRelation')->findOrFail($id);

        $ringkasanPribadi = DB::table(table: 'mindmaps')
            ->where('user', Auth::user()->id)
            ->value('ringkasan_pribadi');

        $ringkasans = Ringkasan::where('mindmaps', $id)
            ->with('userRelation')
            ->get()
            ->map(function ($r) {
                return [
                    'user' => $r->userRelation->name ?? 'Tidak diketahui',
                    'ringkasan' => $r->ringkasan,
                ];
            });

        $komentarLain = DB::table('komentar')
            ->join('users', 'komentar.user', '=', 'users.id')
            ->where('mindmaps', $id)
            // ->orderBy('komentar.created_at', 'desc')
            ->select('users.name as user', 'komentar.komentar')
            ->get()
            ->map(function ($k) {
                return [
                    'user' => $k->user,
                    'komentar' => $k->komentar ?? '-',
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
            'komentar_lain' => $komentarLain ?? [],
            'current_user_id' => Auth::user()->name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function toggleShare($id)
    {
        $mindmap = Mindmap::findOrFail($id);

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

        try {
            // Simpan ringkasan (update jika sudah ada, atau buat baru)
            Ringkasan::updateOrCreate(
                [
                    'user' => Auth::user()->id,
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
