<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('materi', [
            'materi' => Materi::orderBy('created_at', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'nama_materi' => 'required|string|max:255',
                'tipe_file' => 'required|in:text,dokumen',
                'deskripsi' => 'nullable|string',
                'file' => 'nullable|file|mimes:pdf|max:20480'
            ]) + ['file' => null];

            if ($validatedData['tipe_file'] === 'dokumen') {
                if (!$request->hasFile('file')) {
                    return redirect()->back()
                        ->withErrors(['file' => 'File PDF wajib diunggah untuk tipe dokumen.'])
                        ->withInput();
                }

                $path = $request->file('file')->store('materi', 'public');
                $validatedData['file'] = basename($path);
            }

            // Simpan ke database
            Materi::create([
                'nama_materi' => $validatedData['nama_materi'],
                'tipe_file' => $validatedData['tipe_file'],
                'deskripsi' => $validatedData['deskripsi'] ?? null,
                'file' => $validatedData['file'],
            ]);

            DB::commit();

            return redirect()->route('materi')->with('toast', [
                'message' => 'Materi berhasil ditambahkan.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'message' => 'Gagal menambahkan materi. Silakan coba lagi. Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);

        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tipe_file' => 'sometimes|in:text,dokumen',
                'file' => 'nullable|file|mimes:pdf|max:20480'
            ]);

            $materi->nama_materi = $validatedData['judul'];
            $materi->deskripsi = $validatedData['deskripsi'];

            if ($materi->tipe_file === 'dokumen' && $request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($materi->file && Storage::disk('public')->exists('materi/' . $materi->file)) {
                    Storage::disk('public')->delete('materi/' . $materi->file);
                }

                // Simpan file baru
                $path = $request->file('file')->store('materi', 'public');
                $materi->file = basename($path);
            }

            $materi->save();

            DB::commit();

            return redirect()->route('materi')->with('toast', [
                'message' => 'Materi berhasil diperbarui.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'message' => 'Gagal memperbarui materi. Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $materi = Materi::findOrFail($id);

            // Hapus file dari storage jika ada dan bertipe dokumen
            if ($materi->tipe_file === 'dokumen' && $materi->file) {
                $filePath = storage_path('app/public/materi/' . $materi->file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus data dari database
            $materi->delete();

            DB::commit();

            return redirect()->route('materi')->with('toast', [
                'message' => 'Materi berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'message' => 'Gagal menghapus materi. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }
}
