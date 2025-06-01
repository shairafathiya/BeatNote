<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $songs = Song::with('note')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $notes = Note::all();

        return view('music', compact('songs', 'notes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg,m4a,flac|max:20480', // max 20MB
            'genre' => 'nullable|in:Pop,Rock,Jazz,Hip-Hop,Folk',
            'status' => 'nullable|in:Draft,Mixing,Selesai',
            'note_id' => 'nullable|exists:notes,id',
        ]);

        try {
            $file = $request->file('audio_file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('songs', $fileName, 'public');

            Song::create([
                'title' => $request->title,
                'file_path' => $filePath,
                'genre' => $request->genre,
                'status' => $request->status,
                'note_id' => $request->note_id ?: null,
            ]);

            return redirect()->back()->with('success', 'Lagu berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan lagu: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'nullable|in:Pop,Rock,Jazz,Hip-Hop,Folk',
            'status' => 'nullable|in:Draft,Mixing,Selesai',
            'note_id' => 'nullable|exists:notes,id',
        ]);

        $song->update([
            'title' => $request->title,
            'genre' => $request->genre,
            'status' => $request->status,
            'note_id' => $request->note_id ?: null,
        ]);

        return redirect()->back()->with('success', 'Lagu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        try {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($song->file_path)) {
                Storage::disk('public')->delete($song->file_path);
            }

            // Hapus record dari database
            $song->delete();

            return redirect()->back()->with('success', 'Lagu berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus lagu: ' . $e->getMessage());
        }
    }

    /**
     * Filter songs by genre or status
     */
    public function filter(Request $request)
    {
        $query = Song::with('note');

        if ($request->genre) {
            $query->byGenre($request->genre);
        }

        if ($request->status) {
            $query->byStatus($request->status);
        }

        $songs = $query->orderBy('created_at', 'desc')->get();
        $notes = Note::all();

        return view('music', compact('songs', 'notes'));
    }
}