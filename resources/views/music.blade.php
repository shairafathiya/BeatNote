<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagu & Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f9f4e8] text-[#3b362f]">
    <div class="flex flex-col p-6 gap-4 min-h-screen max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold">🎵 Lagu & Demo</h1>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPLOAD --}}
        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <input type="file" name="audio_file" id="fileInput" accept="audio/*" 
                           class="w-full border p-2 rounded" required>

                    <input type="text" name="title" id="titleInput" placeholder="Judul lagu" 
                           class="w-full border p-2 rounded" value="{{ old('title') }}" required>

                    <select name="genre" id="genreSelect" class="w-full border p-2 rounded">
                        <option value="">Pilih Genre</option>
                        @foreach (['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'] as $genre)
                            <option value="{{ $genre }}" {{ old('genre') == $genre ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" id="statusSelect" class="w-full border p-2 rounded">
                        <option value="">Pilih Status</option>
                        @foreach (['Draft', 'Mixing', 'Selesai'] as $status)
                            <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <select name="note_id" id="noteSelect" class="w-full border p-2 rounded">
                        <option value="">Terkait Catatan (opsional)</option>
                        @foreach ($notes as $note)
                            <option value="{{ $note->id }}" {{ old('note_id') == $note->id ? 'selected' : '' }}>
                                {{ $note->title }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-[#5f3d3d] text-white px-4 py-2 rounded hover:bg-[#472d2d]">
                        Tambah Lagu
                    </button>
                </div>
            </form>
        </div>

        {{-- FILTER SECTION --}}
        <div class="bg-white p-4 rounded-xl shadow">
            <form action="{{ route('songs.filter') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex gap-2">
                    <select name="genre" class="border p-2 rounded">
                        <option value="">Semua Genre</option>
                        @foreach (['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'] as $genre)
                            <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="border p-2 rounded">
                        <option value="">Semua Status</option>
                        @foreach (['Draft', 'Mixing', 'Selesai'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>

                <a href="{{ route('songs.index') }}" class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
            </form>
        </div>

        {{-- LIST SONGS --}}
        <div class="space-y-4">
            @forelse($songs as $song)
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold">{{ $song->title }}</h2>
                        <div class="flex gap-2">
                            <button onclick="editSong({{ $song->id }})" 
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-sm hover:bg-yellow-600">
                                Edit
                            </button>
                            <form action="{{ route('songs.destroy', $song) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus lagu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <audio controls src="{{ $song->file_url }}" class="w-full mb-2"></audio>
                    
                    <div class="flex gap-4 text-sm text-gray-600">
                        @if($song->genre)
                            <span>🎼 Genre: {{ $song->genre }}</span>
                        @endif
                        @if($song->status)
                            <span>📌 Status: {{ $song->status }}</span>
                        @endif
                        @if($song->note)
                            <span>📝 Catatan: {{ $song->note->title }}</span>
                        @endif
                        <span>📅 {{ $song->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    {{-- FORM EDIT (Hidden by default) --}}
                    <div id="editForm{{ $song->id }}" class="hidden mt-4 border-t pt-4">
                        <form action="{{ route('songs.update', $song) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="title" value="{{ $song->title }}" 
                                       class="border p-2 rounded" required>
                                
                                <select name="genre" class="border p-2 rounded">
                                    <option value="">Pilih Genre</option>
                                    @foreach (['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'] as $genre)
                                        <option value="{{ $genre }}" {{ $song->genre == $genre ? 'selected' : '' }}>
                                            {{ $genre }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="status" class="border p-2 rounded">
                                    <option value="">Pilih Status</option>
                                    @foreach (['Draft', 'Mixing', 'Selesai'] as $status)
                                        <option value="{{ $status }}" {{ $song->status == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="note_id" class="border p-2 rounded">
                                    <option value="">Terkait Catatan (opsional)</option>
                                    @foreach ($notes as $note)
                                        <option value="{{ $note->id }}" {{ $song->note_id == $note->id ? 'selected' : '' }}>
                                            {{ $note->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex gap-2 mt-4">
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Simpan
                                </button>
                                <button type="button" onclick="cancelEdit({{ $song->id }})" 
                                        class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded shadow text-center text-gray-500">
                    <p>Belum ada lagu yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Auto-fill title from filename
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const titleInput = document.getElementById('titleInput');
                if (!titleInput.value) {
                    titleInput.value = file.name.replace(/\.[^/.]+$/, "");
                }
            }
        });

        // Edit song functions
        function editSong(songId) {
            document.getElementById('editForm' + songId).classList.remove('hidden');
        }

        function cancelEdit(songId) {
            document.getElementById('editForm' + songId).classList.add('hidden');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>