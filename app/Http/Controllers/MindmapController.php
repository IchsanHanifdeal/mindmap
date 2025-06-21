<?php

namespace App\Http\Controllers;

use App\Models\Mindmap;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MindmapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mindmap.index');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Mindmap $mindmap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mindmap $mindmap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mindmap $mindmap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mindmap $mindmap)
    {
        //
    }
}
