<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Note;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function create()
    {
        $notes = Note::all();
        return view('music', compact('notes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'note_id' => 'nullable|exists:notes,id'
        ]);

        $audioPath = $request->file('audio_file')->store('audio', 'public');

        Song::create([
            'title' => $validated['title'],
            'genre' => $validated['genre'] ?? null,
            'status' => $validated['status'] ?? null,
            'audio_path' => $audioPath,
            'note_id' => $validated['note_id'] ?? null
        ]);

        return redirect()->route('songs.create')->with('success', 'Lagu berhasil disimpan!');
    }
}
