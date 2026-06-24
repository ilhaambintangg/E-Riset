<?php

namespace App\Http\Controllers;

use App\Models\Panitera;
use Illuminate\Http\Request;

class PaniteraController extends Controller
{
    public function index()
    {
        $paniteras = Panitera::orderBy('created_at', 'desc')->get();
        return view('admin.panitera.index', compact('paniteras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panitera' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:panitera',
            'jabatan' => 'required|string|max:255',
            'status_aktif' => 'boolean',
        ]);

        Panitera::create($validated);
        return back()->with('success', 'Data panitera berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $panitera = Panitera::findOrFail($id);

        $validated = $request->validate([
            'nama_panitera' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:panitera,nip,' . $panitera->id,
            'jabatan' => 'required|string|max:255',
            'status_aktif' => 'boolean',
        ]);

        $panitera->update($validated);
        return back()->with('success', 'Data panitera berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panitera = Panitera::findOrFail($id);
        $panitera->delete();
        return back()->with('success', 'Data panitera berhasil dihapus.');
    }
}
