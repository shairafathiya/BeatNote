<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    /**
     * Display a listing of the notes.
     */
    public function index(Request $request): View
    {
        $query = Note::query()->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by tag
        if ($request->has('tag') && !empty($request->tag)) {
            $query->withTag($request->tag);
        }

        $notes = $query->get();
        
        return view('notes', compact('notes'));
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(): View
    {
        return view('notes.create');
    }

    /**
     * Store a newly created note in storage.
     */
       public function store(Request $request)
    {       
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'audio' => 'nullable|file|mimes:mp3,wav,ogg',
                'tags' => 'nullable|array',
            ]);

            $note = new Note();
            $note->title = $validated['title'];
            $note->content = $validated['content'];

            if ($request->hasFile('audio')) {
                $note->audio = $request->file('audio')->store('audio', 'public');
            }

            if ($request->filled('tags')) {
                $note->tags = json_encode($validated['tags']);
            }

            $note->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'note' => [
                        'id' => $note->id,
                        'title' => $note->title,
                        'content' => $note->content,
                        'audio_url' => $note->audio ? asset('storage/' . $note->audio) : null,
                        'tags' => $validated['tags'] ?? []
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Catatan berhasil disimpan.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan catatan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan catatan.')
                ->withInput();
        }
    }

            

    /**
     * Display the specified note.
     */
    public function show(Note $note): View
    {
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(Note $note): View
    {
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, Note $note)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:lirik,chord,ide,draft,selesai'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'tags' => $request->tags ?? [],
            ];

            // Handle audio file upload
            if ($request->hasFile('audio')) {
                // Delete old audio file if exists
                if ($note->audio && Storage::disk('public')->exists($note->audio)) {
                    Storage::disk('public')->delete($note->audio);
                }

                $audioFile = $request->file('audio');
                $fileName = time() . '_' . $audioFile->getClientOriginalName();
                $audioPath = $audioFile->storeAs('audio', $fileName, 'public');
                $data['audio'] = $audioPath;
            }

            $note->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catatan berhasil diperbarui!',
                    'note' => $note
                ]);
            }

            return redirect()->route('notes.index')
                ->with('success', 'Catatan berhasil diperbarui!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui catatan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui catatan.')
                ->withInput();
        }
    }

    /**
     * Remove the specified note from storage.
     */
    public function destroy(Note $note)
    {
        try {
            // Delete audio file if exists
            if ($note->audio && Storage::disk('public')->exists($note->audio)) {
                Storage::disk('public')->delete($note->audio);
            }

            $note->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catatan berhasil dihapus!'
                ]);
            }

            return redirect()->route('notes.index')
                ->with('success', 'Catatan berhasil dihapus!');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus catatan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus catatan.');
        }
    }

    /**
     * Get notes filtered by tag via AJAX.
     */
    public function getByTag(Request $request): JsonResponse
    {
        $tag = $request->get('tag');
        
        if (!$tag) {
            $notes = Note::orderBy('created_at', 'desc')->get();
        } else {
            $notes = Note::withTag($tag)->orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'success' => true,
            'notes' => $notes
        ]);
    }

    /**
     * Search notes via AJAX.
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $notes = Note::search($search)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'notes' => $notes
        ]);
    }
}