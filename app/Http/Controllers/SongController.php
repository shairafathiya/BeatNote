<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::with('note')->latest()->get();
        $notes = Note::all(); // For dropdown
        
        return view('music.index', compact('songs', 'notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|mimes:mp3,wav,ogg,m4a|max:10240', // Max 10MB
            'title' => 'required|string|max:255',
            'genre' => 'required|string|in:Pop,Rock,Jazz,Hip-Hop,Folk',
            'status' => 'required|string|in:Draft,Mixing,Selesai',
            'note_id' => 'nullable|exists:notes,id'
        ]);

        $audioFile = $request->file('audio_file');
        $filePath = $audioFile->store('songs', 'public');

        Song::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'genre' => $request->genre,
            'status' => $request->status,
            'note_id' => $request->note_id,
            'user_id' => auth()->id(), // Assuming user authentication
        ]);

        return redirect()->route('songs.index')
                        ->with('success', 'Lagu berhasil ditambahkan!');
    }

    public function edit(Song $song)
    {
        $notes = Note::all();
        $genres = ['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'];
        $statuses = ['Draft', 'Mixing', 'Selesai'];
        
        return view('music.edit', compact('song', 'notes', 'genres', 'statuses'));
    }

    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|in:Pop,Rock,Jazz,Hip-Hop,Folk',
            'status' => 'required|string|in:Draft,Mixing,Selesai',
            'note_id' => 'nullable|exists:notes,id',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240'
        ]);

        $updateData = [
            'title' => $request->title,
            'genre' => $request->genre,
            'status' => $request->status,
            'note_id' => $request->note_id,
        ];

        // If new audio file is uploaded
        if ($request->hasFile('audio_file')) {
            // Delete old file
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            
            // Store new file
            $audioFile = $request->file('audio_file');
            $updateData['file_path'] = $audioFile->store('songs', 'public');
        }

        $song->update($updateData);

        return redirect()->route('songs.index')
                        ->with('success', 'Lagu berhasil diperbarui!');
    }

    public function destroy(Song $song)
    {
        // Delete audio file
        if ($song->file_path) {
            Storage::disk('public')->delete($song->file_path);
        }

        $song->delete();

        return redirect()->route('songs.index')
                        ->with('success', 'Lagu berhasil dihapus!');
    }
}