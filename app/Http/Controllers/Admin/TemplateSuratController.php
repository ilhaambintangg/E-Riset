<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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

    public function store(\App\Http\Requests\Admin\TemplateSurat\StoreTemplateSuratRequest $request)
    {

        if ($request->hasFile('template')) {
            $file = $request->file('template');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $fileName, 'public');

            // Set all other templates of the same type to inactive
            TemplateSurat::where('type', $request->type)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            TemplateSurat::create([
                'file_path' => $path,
                'type' => $request->type,
                'is_active' => true,
            ]);

            return back()->with('success', 'Template surat ' . $request->type . ' berhasil diunggah dan diaktifkan.');
        }

        return back()->withErrors(['template' => 'Gagal mengunggah template.']);
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        $wasActive = $template->is_active;
        $type = $template->type;

        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();

        if ($wasActive) {
            // Find the next most recent template of the same type and make it active
            $nextTemplate = TemplateSurat::where('type', $type)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($nextTemplate) {
                $nextTemplate->update(['is_active' => true]);
            }
        }

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
