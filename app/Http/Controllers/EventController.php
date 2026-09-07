<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->latest()
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_start' => 'required|date',
            'event_end' => 'nullable|date|after_or_equal:event_start',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        Event::create($validated);

        return redirect()->route('events')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_start' => 'required|date',
            'event_end' => 'nullable|date|after_or_equal:event_start',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $event->update($validated);

        return redirect()->route('events')->with('success', 'Event updated successfully.');
    }
}
