<?php

namespace App\Http\Controllers;

use App\Models\KataKunci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KataKunciController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return KataKunci::where('materi', $request->materi)
            ->where('user', Auth::user()->id)
            ->get();
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
        return KataKunci::create([
            'materi' => $request->materi,
            'user' => $request->user,
            'kata_kunci' => $request->kata_kunci
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KataKunci $kataKunci)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KataKunci $kataKunci)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kk = KataKunci::findOrFail($id);
        $kk->update(['kata_kunci' => $request->kata_kunci]);
        return $kk;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kk = KataKunci::findOrFail($id);
        $kk->delete();
        return response()->json(['success' => true]);
    }
}
