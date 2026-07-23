<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::where('is_approved', true)->orderBy('name', 'asc')->get();
        $pendingUniversities = University::where('is_approved', false)->orderBy('name', 'asc')->get();
        return view('admin.universities.index', compact('universities', 'pendingUniversities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:universities,name'],
        ], [
            'name.required' => 'Nama universitas wajib diisi.',
            'name.unique' => 'Nama universitas sudah terdaftar.',
        ]);

        $validated['is_approved'] = true;

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

    public function approve($id)
    {
        $university = University::findOrFail($id);
        $university->update(['is_approved' => true]);
        return back()->with('success', 'Universitas berhasil disetujui.');
    }
}
