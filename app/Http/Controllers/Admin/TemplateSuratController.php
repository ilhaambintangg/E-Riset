<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateSurat;
use App\Http\Requests\Admin\TemplateSurat\StoreTemplateSuratRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateSurat::with('uploader')->orderBy('created_at', 'desc');

        if ($request->filled('institution_type')) {
            $query->where('institution_type', $request->institution_type);
        }
        if ($request->filled('template_type')) {
            $query->where('template_type', $request->template_type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('upload_date')) {
            $query->whereDate('created_at', $request->upload_date);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $templates = $query->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function store(StoreTemplateSuratRequest $request)
    {
        if ($request->hasFile('template')) {
            $file = $request->file('template');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $fileName, 'public');

            // Calculate next version for this specific category
            $maxVersion = TemplateSurat::where('institution_type', $request->institution_type)
                ->where('template_type', $request->template_type)
                ->max('version');
            $nextVersion = ($maxVersion ?? 0) + 1;

            // Deactivate other templates in the same category
            TemplateSurat::where('institution_type', $request->institution_type)
                ->where('template_type', $request->template_type)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Create template
            TemplateSurat::create([
                'name' => $file->getClientOriginalName(),
                'institution_type' => $request->institution_type,
                'template_type' => $request->template_type,
                'file_path' => $path,
                'version' => $nextVersion,
                'is_active' => true,
                'uploaded_by' => auth()->id(),
            ]);

            $categoryLabel = $request->institution_type . ' - ' . ucfirst($request->template_type);
            return redirect()->route('templates.index')->with('success', "Template surat {$categoryLabel} berhasil diunggah (v{$nextVersion}) dan diaktifkan.");
        }

        return redirect()->route('templates.index')->withErrors(['template' => 'Gagal mengunggah template.']);
    }

    public function activate($id)
    {
        $template = TemplateSurat::findOrFail($id);

        // Deactivate other templates in the same category
        TemplateSurat::where('institution_type', $template->institution_type)
            ->where('template_type', $template->template_type)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $template->update(['is_active' => true]);

        $categoryLabel = $template->institution_type . ' - ' . ucfirst($template->template_type);
        return redirect()->route('templates.index')->with('success', "Template {$categoryLabel} versi {$template->version} berhasil diaktifkan.");
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        $wasActive = $template->is_active;
        $institutionType = $template->institution_type;
        $templateType = $template->template_type;

        // Clean up from storage
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();

        if ($wasActive) {
            // Find the next most recent template in the same category and activate it
            $nextTemplate = TemplateSurat::where('institution_type', $institutionType)
                ->where('template_type', $templateType)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($nextTemplate) {
                $nextTemplate->update(['is_active' => true]);
            }
        }

        return redirect()->route('templates.index')->with('success', 'Template berhasil dihapus.');
    }

    public function download($id)
    {
        $template = TemplateSurat::findOrFail($id);
        if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
            return Storage::disk('public')->download($template->file_path);
        }

        return redirect()->route('templates.index')->withErrors(['error' => 'Template surat tidak ditemukan']);
    }
}
