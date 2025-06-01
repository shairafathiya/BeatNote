<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(): View
    {
        $events = Event::getEventsByDate();
        return view('events', compact('events'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:Manggung,Latihan,Recording'
        ]);

        Event::create([
            'title' => $request->title,
            'date' => $request->date,
            'type' => $request->type
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }
}