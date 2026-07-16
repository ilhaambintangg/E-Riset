<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::orderBy('name', 'asc')->get();
        return view('admin.universities.index', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:universities,name'],
        ], [
            'name.required' => 'Nama universitas wajib diisi.',
            'name.unique' => 'Nama universitas sudah terdaftar.',
        ]);

        University::create($validated);
        return back()->with('success', 'Data universitas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $university = University::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:universities,name,' . $id],
        ], [
            'name.required' => 'Nama universitas wajib diisi.',
            'name.unique' => 'Nama universitas sudah terdaftar.',
        ]);

        $university->update($validated);
        return back()->with('success', 'Data universitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $university = University::findOrFail($id);
        $university->delete();
        return back()->with('success', 'Data universitas berhasil dihapus.');
    }
}
