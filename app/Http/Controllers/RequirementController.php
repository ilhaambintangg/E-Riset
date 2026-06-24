<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requirement;

class RequirementController extends Controller
{
    public function index()
    {
        $requirements = Requirement::orderBy('created_at', 'asc')->get();
        return view('admin.requirements.index', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        Requirement::create($validated);
        return back()->with('success', 'Persyaratan dokumen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $requirement = Requirement::findOrFail($id);
        return response()->json($requirement);
    }

    public function update(Request $request, $id)
    {
        $requirement = Requirement::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        $requirement->update($validated);
        return back()->with('success', 'Persyaratan dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $requirement = Requirement::findOrFail($id);
        $requirement->delete();
        return back()->with('success', 'Persyaratan dokumen berhasil dihapus.');
    }
}
