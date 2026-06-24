<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index()
    {
        return response()->json(TemplateSurat::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'template' => 'required|file|mimes:docx|max:5120',
        ]);

        if ($request->hasFile('template')) {
            $file = $request->file('template');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $fileName, 'public');

            // Set all other templates to inactive
            TemplateSurat::where('is_active', true)->update(['is_active' => false]);

            $template = TemplateSurat::create([
                'file_path' => $path,
                'is_active' => true,
            ]);

            return response()->json($template, 201);
        }

        return response()->json(['message' => 'Failed to upload template'], 400);
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();
        return response()->json(['message' => 'Template deleted successfully']);
    }
}
