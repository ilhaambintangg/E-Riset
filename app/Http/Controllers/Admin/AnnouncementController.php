<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(\App\Http\Requests\Admin\Announcement\StoreAnnouncementRequest $request)
    {
        $validated = $request->validated();

        Announcement::create($validated);
        return back()->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(\App\Http\Requests\Admin\Announcement\UpdateAnnouncementRequest $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validated();

        $announcement->update($validated);
        return back()->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
