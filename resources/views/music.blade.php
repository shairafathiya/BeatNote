@extends('layouts.app')

@section('title', 'Lagu & Demo')

@section('content')
<div class="flex flex-col p-6 gap-2 text-[#3b362f] bg-[#f9f4e8] min-h-screen">
    <h1 class="text-3xl font-bold">🎵 Lagu & Demo</h1>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <form id="songForm" action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input
                type="file"
                name="audio_file"
                id="audioFile"
                accept="audio/*"
                class="w-full"
                required
            />

            <div id="formFields" style="display: none;">
                <input
                    type="text"
                    name="title"
                    id="title"
                    placeholder="Judul lagu"
                    class="w-full border p-2 rounded"
                    required
                />

                <select
                    name="genre"
                    class="w-full border p-2 rounded"
                    required
                >
                    <option value="">Pilih Genre</option>
                    @foreach(['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'] as $genre)
                        <option value="{{ $genre }}">{{ $genre }}</option>
                    @endforeach
                </select>

                <select
                    name="status"
                    class="w-full border p-2 rounded"
                    required
                >
                    <option value="">Pilih Status</option>
                    @foreach(['Draft', 'Mixing', 'Selesai'] as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>

                <select
                    name="note_id"
                    class="w-full border p-2 rounded"
                >
                    <option value="">Terkait Catatan (opsional)</option>
                    @foreach($notes as $note)
                        <option value="{{ $note->id }}">{{ $note->title }}</option>
                    @endforeach
                </select>

                <button
                    type="submit"
                    class="bg-[#5f3d3d] text-white px-4 py-2 rounded hover:bg-[#472d2d] transition-colors"
                >
                    Tambah Lagu
                </button>
            </div>
        </form>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- LIST -->
    <div class="space-y-4">
        @forelse($songs as $song)
            <div class="bg-white p-4 rounded shadow flex flex-col gap-2">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-bold">{{ $song->title }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('songs.edit', $song->id) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('songs.destroy', $song->id) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus lagu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
                
                <audio controls class="w-full">
                    <source src="{{ Storage::url($song->file_path) }}" type="audio/mpeg">
                    <source src="{{ Storage::url($song->file_path) }}" type="audio/wav">
                    <source src="{{ Storage::url($song->file_path) }}" type="audio/ogg">
                    Browser Anda tidak mendukung audio player.
                </audio>

                <div class="flex gap-4 text-sm text-gray-600">
                    <span>🎼 Genre: {{ $song->genre }}</span>
                    <span>📌 Status: {{ $song->status }}</span>
                    @if($song->note)
                        <span>📝 Catatan: {{ $song->note->title }}</span>
                    @endif
                </div>
                
                <div class="text-xs text-gray-500">
                    Ditambahkan: {{ $song->created_at->format('d M Y H:i') }}
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded shadow text-center text-gray-500">
                <p>Belum ada lagu yang ditambahkan.</p>
                <p class="mt-2">Upload file audio pertama Anda!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('audioFile');
    const formFields = document.getElementById('formFields');
    const titleInput = document.getElementById('title');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show form fields
            formFields.style.display = 'block';
            
            // Auto-fill title from filename
            const fileName = file.name.replace(/\.[^/.]+$/, '');
            titleInput.value = fileName;
        } else {
            // Hide form fields if no file selected
            formFields.style.display = 'none';
            titleInput.value = '';
        }
    });
});
</script>
@endsection