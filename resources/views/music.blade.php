<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lagu & Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f4e8] text-[#3b362f]">
    <div class="flex flex-col p-6 gap-4 min-h-screen max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold">🎵 Lagu & Demo</h1>

        {{-- FORM UPLOAD --}}
        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <input type="file" id="fileInput" accept="audio/*" class="w-full border p-2 rounded">

            <input type="text" id="titleInput" placeholder="Judul lagu" class="w-full border p-2 rounded">

            <select id="genreSelect" class="w-full border p-2 rounded">
                <option value="">Pilih Genre</option>
                @foreach (['Pop', 'Rock', 'Jazz', 'Hip-Hop', 'Folk'] as $genre)
                    <option value="{{ $genre }}">{{ $genre }}</option>
                @endforeach
            </select>

            <select id="statusSelect" class="w-full border p-2 rounded">
                <option value="">Pilih Status</option>
                @foreach (['Draft', 'Mixing', 'Selesai'] as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>

            <select id="noteSelect" class="w-full border p-2 rounded">
                <option value="">Terkait Catatan (opsional)</option>
                @foreach ($notes as $note)
                    <option value="{{ $note->id }}">{{ $note->title }}</option>
                @endforeach
            </select>

            <button id="addSongBtn" class="bg-[#5f3d3d] text-white px-4 py-2 rounded hover:bg-[#472d2d]">
                Tambah Lagu
            </button>
        </div>

        {{-- LIST SONGS --}}
        <div id="songsList" class="space-y-4">
            {{-- Lagu akan ditambahkan lewat JS --}}
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const titleInput = document.getElementById('titleInput');
        const genreSelect = document.getElementById('genreSelect');
        const statusSelect = document.getElementById('statusSelect');
        const noteSelect = document.getElementById('noteSelect');
        const addSongBtn = document.getElementById('addSongBtn');
        const songsList = document.getElementById('songsList');

        const dummyNotes = @json($notes);
        const songs = [];

        let currentFileUrl = null;

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (file) {
                if (currentFileUrl) URL.revokeObjectURL(currentFileUrl);
                currentFileUrl = URL.createObjectURL(file);
                titleInput.value = file.name.replace(/\.[^/.]+$/, "");
            }
        });

        addSongBtn.addEventListener('click', () => {
            const title = titleInput.value.trim();
            const genre = genreSelect.value;
            const status = statusSelect.value;
            const noteId = noteSelect.value;
            const noteTitle = dummyNotes.find(n => n.id == noteId)?.title || "";

            if (!title || !currentFileUrl) {
                alert("Lengkapi file dan judul lagu!");
                return;
            }

            const song = {
                id: Date.now(),
                title,
                genre,
                status,
                noteTitle,
                fileUrl: currentFileUrl
            };

            songs.unshift(song);
            renderSongs();

            // Reset form
            fileInput.value = "";
            titleInput.value = "";
            genreSelect.value = "";
            statusSelect.value = "";
            noteSelect.value = "";
            currentFileUrl = null;
        });

        function renderSongs() {
            songsList.innerHTML = songs.map(song => `
                <div class="bg-white p-4 rounded shadow flex flex-col gap-2">
                    <h2 class="text-xl font-bold">${song.title}</h2>
                    <audio controls src="${song.fileUrl}" class="w-full"></audio>
                    <div class="flex gap-4 text-sm text-gray-600">
                        ${song.genre ? `<span>🎼 Genre: ${song.genre}</span>` : ""}
                        ${song.status ? `<span>📌 Status: ${song.status}</span>` : ""}
                        ${song.noteTitle ? `<span>📝 Catatan: ${song.noteTitle}</span>` : ""}
                    </div>
                </div>
            `).join('');
        }
    </script>
</body>
</html>
