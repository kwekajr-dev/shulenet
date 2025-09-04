<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AnnouncementPublished;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('teacher')
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'string|max:100'
        ]);

        $announcement = Announcement::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'target_audience' => $request->target_audience
        ]);

        // Send notifications to relevant parents
        $this->notifyParents($announcement);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'string|max:100'
        ]);

        $announcement->update($request->all());

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    protected function notifyParents($announcement)
    {
        if ($announcement->isForAll()) {
            // Notify all parents
            $parents = User::where('type', 'parent')->get();
        } else {
            // Notify parents of students in specific classes
            $parents = User::where('type', 'parent')
                ->whereHas('students.classAssignments', function($query) use ($announcement) {
                    $query->whereIn('class_name', $announcement->target_classes);
                })
                ->get();
        }

        foreach ($parents as $parent) {
            $parent->notify(new AnnouncementPublished($announcement));
        }
    }
}