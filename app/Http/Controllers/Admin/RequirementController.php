<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Requirement;

class RequirementController extends Controller
{
    public function index()
    {
        $requirements = Requirement::orderBy('created_at', 'asc')->get();
        return view('admin.requirements.index', compact('requirements'));
    }

    public function store(\App\Http\Requests\Admin\Requirement\StoreRequirementRequest $request)
    {
        $validated = $request->validated();

        Requirement::create($validated);
        return back()->with('success', 'Persyaratan dokumen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $requirement = Requirement::findOrFail($id);
        return response()->json($requirement);
    }

    public function update(\App\Http\Requests\Admin\Requirement\UpdateRequirementRequest $request, $id)
    {
        $requirement = Requirement::findOrFail($id);

        $validated = $request->validated();

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
