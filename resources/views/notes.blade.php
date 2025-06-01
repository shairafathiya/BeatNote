<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎶 Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #faf6ee;
            color: #3b362f;
        }
    </style>
</head>
<body class="bg-[#faf6ee] flex flex-col min-h-screen">
    <div class="p-8 max-w-4xl mx-auto space-y-8 text-[#3b362f] bg-[#faf6ee] min-h-screen">
        <h1 class="text-3xl font-bold">🎶 Notes</h1>

        <!-- FORM -->
        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <form id="noteForm" enctype="multipart/form-data">
                @csrf
                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Judul"
                    class="w-full border p-2 rounded"
                    required
                />
                <textarea
                    id="content"
                    name="content"
                    placeholder="Isi catatan"
                    class="w-full border p-2 rounded mt-4"
                    rows="4"
                    required
                ></textarea>
                
                <div class="mt-4">
                    <label class="block mb-1">Upload Audio (opsional)</label>
                    <input 
                        class="text-amber-800 hover:underline" 
                        type="file" 
                        id="audio"
                        name="audio"
                        accept="audio/*"
                    />
                </div>
                
                <div class="flex flex-wrap gap-2 mt-4">
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="tags[]" value="lirik" class="tag-checkbox" />
                        <span class="text-sm capitalize">lirik</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="tags[]" value="chord" class="tag-checkbox" />
                        <span class="text-sm capitalize">chord</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="tags[]" value="ide" class="tag-checkbox" />
                        <span class="text-sm capitalize">ide</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="tags[]" value="draft" class="tag-checkbox" />
                        <span class="text-sm capitalize">draft</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="tags[]" value="selesai" class="tag-checkbox" />
                        <span class="text-sm capitalize">selesai</span>
                    </label>
                </div>
                
                <button
                    type="submit"
                    class="bg-[#5f3d3d] text-white px-4 py-2 rounded hover:bg-[#472d2d] mt-4"
                >
                    Tambah Catatan
                </button>
            </form>
        </div>

        <!-- FILTER -->
        <div>
            <input
                type="text"
                id="filterInput"
                placeholder="Cari judul..."
                class="w-full border p-2 rounded"
            />
        </div>

        <!-- LIST NOTES -->
        <div id="notesList" class="space-y-4">
            @forelse($notes as $note)
                <div class="bg-white p-4 rounded shadow flex flex-col gap-2" data-title="{{ strtolower($note->title) }}">
                    <div class="flex justify-between items-center">
                        <h2 class="font-bold text-xl">{{ $note->title }}</h2>
                        <button
                            onclick="deleteNote({{ $note->id }})"
                            class="text-red-500 hover:underline"
                        >
                            Hapus
                        </button>
                    </div>
                    <p>{{ $note->content }}</p>
                    
                    @if($note->audio_path)
                        <audio controls class="w-full">
                            <source src="{{ asset('storage/' . $note->audio_path) }}" />
                            Browser kamu tidak mendukung audio
                        </audio>
                    @endif
                    
                    @if($note->tags)
                        <div class="flex gap-2 text-sm text-gray-600">
                            @php
                                // Safe decode tags - handle both string and array cases
                                $tags = [];
                                if (is_string($note->tags)) {
                                    $decoded = json_decode($note->tags, true);
                                    $tags = is_array($decoded) ? $decoded : [];
                                } elseif (is_array($note->tags)) {
                                    $tags = $note->tags;
                                }
                            @endphp
                            
                            @if(count($tags) > 0)
                                @foreach($tags as $tag)
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <p>Belum ada catatan. Tambahkan catatan pertama Anda!</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Get DOM elements
        const noteForm = document.getElementById('noteForm');
        const filterInput = document.getElementById('filterInput');
        const notesList = document.getElementById('notesList');

        // Add note function
        async function addNote(event) {
            event.preventDefault();
            
            const formData = new FormData(noteForm);
            
            try {
                const response = await fetch('{{ route("notes.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Success - reload page to show new note
                    window.location.reload();
                } else {
                    // Handle validation errors
                    alert(result.message || 'Terjadi kesalahan saat menyimpan catatan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan catatan');
            }
        }

        // Delete note function
        async function deleteNote(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
                return;
            }

            try {
                const response = await fetch(`{{ url('notes') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Success - reload page
                    window.location.reload();
                } else {
                    alert(result.message || 'Terjadi kesalahan saat menghapus catatan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus catatan');
            }
        }

        // Filter notes function (client-side filtering)
        function filterNotes() {
            const filterValue = filterInput.value.toLowerCase();
            const noteElements = notesList.querySelectorAll('[data-title]');
            
            noteElements.forEach(noteElement => {
                const title = noteElement.getAttribute('data-title');
                if (title.includes(filterValue)) {
                    noteElement.style.display = 'flex';
                } else {
                    noteElement.style.display = 'none';
                }
            });
        }

        // Event listeners
        noteForm.addEventListener('submit', addNote);
        filterInput.addEventListener('input', filterNotes);
    </script>
</body>
</html>