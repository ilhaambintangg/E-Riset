<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requirement;

class RequirementController extends Controller
{
    public function index()
    {
        return response()->json(Requirement::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_required' => ['required', 'boolean'],
        ]);

        $requirement = Requirement::create($validated);
        return response()->json($requirement, 201);
    }

    public function show($id)
    {
        $requirement = Requirement::find($id);
        if (!$requirement) {
            return response()->json(['message' => 'Persyaratan tidak ditemukan'], 404);
        }
        return response()->json($requirement);
    }

    public function update(Request $request, $id)
    {
        $requirement = Requirement::find($id);
        if (!$requirement) {
            return response()->json(['message' => 'Persyaratan tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_required' => ['required', 'boolean'],
        ]);

        $requirement->update($validated);
        return response()->json($requirement);
    }

    public function destroy($id)
    {
        $requirement = Requirement::find($id);
        if (!$requirement) {
            return response()->json(['message' => 'Persyaratan tidak ditemukan'], 404);
        }
        $requirement->delete();
        return response()->json(['message' => 'Persyaratan berhasil dihapus']);
    }
}
