<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Panitera;
use Illuminate\Http\Request;

class PaniteraController extends Controller
{
    public function index()
    {
        $paniteras = Panitera::orderBy('created_at', 'desc')->get();
        return view('admin.panitera.index', compact('paniteras'));
    }

    public function store(\App\Http\Requests\Admin\Panitera\StorePaniteraRequest $request)
    {
        $validated = $request->validated();

        Panitera::create($validated);
        return back()->with('success', 'Data panitera berhasil ditambahkan.');
    }

    public function update(\App\Http\Requests\Admin\Panitera\UpdatePaniteraRequest $request, $id)
    {
        $panitera = Panitera::findOrFail($id);

        $validated = $request->validated();

        $panitera->update($validated);
        return back()->with('success', 'Data panitera berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panitera = Panitera::findOrFail($id);
        $panitera->delete();
        return back()->with('success', 'Data panitera berhasil dihapus.');
    }
}
