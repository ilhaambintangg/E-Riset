<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hakim;
use App\Http\Requests\Admin\Hakim\StoreHakimRequest;
use App\Http\Requests\Admin\Hakim\UpdateHakimRequest;
use Illuminate\Http\Request;

class HakimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hakims = Hakim::orderBy('created_at', 'desc')->get();
        return view('admin.hakim.index', compact('hakims'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHakimRequest $request)
    {
        $validated = $request->validated();
        Hakim::create($validated);
        return back()->with('success', 'Data hakim berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHakimRequest $request, $id)
    {
        $hakim = Hakim::findOrFail($id);
        $validated = $request->validated();
        $hakim->update($validated);
        return back()->with('success', 'Data hakim berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hakim = Hakim::findOrFail($id);
        $hakim->delete();
        return back()->with('success', 'Data hakim berhasil dihapus.');
    }
}
