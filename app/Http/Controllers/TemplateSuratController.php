<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::orderBy('created_at', 'desc')->get();
        return view('admin.templates.index', compact('templates'));
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

            TemplateSurat::create([
                'file_path' => $path,
                'is_active' => true,
            ]);

            return back()->with('success', 'Template surat berhasil diunggah dan diaktifkan.');
        }

        return back()->withErrors(['template' => 'Gagal mengunggah template.']);
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();
        return back()->with('success', 'Template berhasil dihapus.');
    }

    public function download($id)
    {
        $template = TemplateSurat::findOrFail($id);
        if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
            return Storage::disk('public')->download($template->file_path);
        }

        return back()->withErrors(['error' => 'Template surat tidak ditemukan']);
    }
}
