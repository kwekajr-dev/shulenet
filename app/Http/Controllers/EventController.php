<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Notifications\EventNotification;
use App\Models\User;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255'
        ]);

        $event = Event::create($request->all());
        
        // Send notification to all parents
        $parents = User::where('type', 'parent')->get();
        foreach ($parents as $parent) {
            $parent->notify(new EventNotification($event));
        }

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }
}