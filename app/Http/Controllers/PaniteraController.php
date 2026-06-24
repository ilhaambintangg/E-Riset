<?php

namespace App\Http\Controllers;

use App\Models\Panitera;
use Illuminate\Http\Request;

class PaniteraController extends Controller
{
    public function index()
    {
        return response()->json(Panitera::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panitera' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:panitera',
            'jabatan' => 'required|string|max:255',
            'status_aktif' => 'boolean',
        ]);

        $panitera = Panitera::create($validated);
        return response()->json($panitera, 201);
    }

    public function show($id)
    {
        return response()->json(Panitera::findOrFail($id));
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
        return response()->json($panitera);
    }

    public function destroy($id)
    {
        $panitera = Panitera::findOrFail($id);
        $panitera->delete();
        return response()->json(['message' => 'Panitera deleted successfully']);
    }
}
