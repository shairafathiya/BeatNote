<?php
// app/Http/Controllers/NoteController.php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Note::orderBy('created_at', 'desc')->get();
        return view('notes', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240', // Max 10MB
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:lirik,chord,ide,draft,selesai'
        ]);

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('audio', 'public');
        }

        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'audio_path' => $audioPath,
            'tags' => $request->tags ? json_encode($request->tags) : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan',
            'note' => $note
        ]);
    }

    public function destroy(Note $note)
    {
        // Delete audio file if exists
        if ($note->audio_path) {
            Storage::disk('public')->delete($note->audio_path);
        }

        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus'
        ]);
    }
}