<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::latest();

        if ($request->filled('importance') && $request->importance !== 'all') {
            $query->where('importance', $request->importance);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('body', 'like', '%' . $request->search . '%');
            });
        }

        $announcements = $query->paginate(9)->withQueryString();

        return view('admin.announcements', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'viewers'    => 'nullable|array',
            'viewers.*'  => 'string|in:All,Admin,Teachers,Students,Parents',
            'importance' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $data['viewers']     = $data['viewers'] ?? ['All'];
        $data['posted_by']   = auth()->user()->name ?? 'Admin';
        $data['school_year'] = \App\Models\SchoolYear::where('status', 'active')->value('name') ?? '2025-2026';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements', ['tab' => 'board'])
            ->with('success', 'Announcement posted successfully.');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'viewers'    => 'nullable|array',
            'viewers.*'  => 'string|in:All,Admin,Teachers,Students,Parents',
            'importance' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $data['viewers'] = $data['viewers'] ?? ['All'];

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $request->file('attachment')
                ->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements', ['tab' => 'board'])
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements', ['tab' => 'board'])
            ->with('success', 'Announcement deleted.');
    }
}
