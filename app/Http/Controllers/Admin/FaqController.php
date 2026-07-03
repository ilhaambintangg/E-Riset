<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('created_at', 'asc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function store(\App\Http\Requests\Admin\Faq\StoreFaqRequest $request)
    {
        $validated = $request->validated();

        Faq::create($validated);
        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function show($id)
    {
        $faq = Faq::findOrFail($id);
        return response()->json($faq);
    }

    public function update(\App\Http\Requests\Admin\Faq\UpdateFaqRequest $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validated();

        $faq->update($validated);
        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return back()->with('success', 'FAQ berhasil dihapus.');
    }
}
