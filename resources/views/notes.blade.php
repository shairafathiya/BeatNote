<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎶 Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <input
                    type="text"
                    id="title"
                    placeholder="Judul"
                    class="w-full border p-2 rounded"
                    required
                />
                <textarea
                    id="content"
                    placeholder="Isi catatan"
                    class="w-full border p-2 rounded mt-4"
                    required
                ></textarea>
                
                <div class="mt-4">
                    <label class="block mb-1">Upload Audio (opsional)</label>
                    <input 
                        class="text-amber-800 hover:underline" 
                        type="file" 
                        id="audio"
                        accept="audio/*"
                    />
                </div>
                
                <div class="flex flex-wrap gap-2 mt-4">
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" value="lirik" class="tag-checkbox" />
                        <span class="text-sm capitalize">lirik</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" value="chord" class="tag-checkbox" />
                        <span class="text-sm capitalize">chord</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" value="ide" class="tag-checkbox" />
                        <span class="text-sm capitalize">ide</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" value="draft" class="tag-checkbox" />
                        <span class="text-sm capitalize">draft</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" value="selesai" class="tag-checkbox" />
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
            <!-- Notes will be dynamically added here -->
        </div>
    </div>

    <script>
        // Initialize notes array
        let notes = [];
        let noteIdCounter = 1;

        // Get DOM elements
        const noteForm = document.getElementById('noteForm');
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const audioInput = document.getElementById('audio');
        const filterInput = document.getElementById('filterInput');
        const notesList = document.getElementById('notesList');
        const tagCheckboxes = document.querySelectorAll('.tag-checkbox');

        // Add note function
        function addNote(event) {
            event.preventDefault();
            
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            
            if (!title || !content) {
                alert('Judul dan isi catatan harus diisi!');
                return;
            }

            // Get selected tags
            const selectedTags = [];
            tagCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedTags.push(checkbox.value);
                }
            });

            // Handle audio file
            let audioURL = null;
            if (audioInput.files && audioInput.files[0]) {
                audioURL = URL.createObjectURL(audioInput.files[0]);
            }

            // Create new note
            const newNote = {
                id: noteIdCounter++,
                title: title,
                content: content,
                audio: audioURL,
                tags: selectedTags
            };

            // Add to notes array
            notes.unshift(newNote);

            // Clear form
            titleInput.value = '';
            contentInput.value = '';
            audioInput.value = '';
            tagCheckboxes.forEach(checkbox => checkbox.checked = false);

            // Render notes
            renderNotes();
        }

        // Delete note function
        function deleteNote(id) {
            notes = notes.filter(note => note.id !== id);
            renderNotes();
        }

        // Filter notes function
        function filterNotes() {
            const filterValue = filterInput.value.toLowerCase();
            const filteredNotes = notes.filter(note => 
                note.title.toLowerCase().includes(filterValue)
            );
            
            renderNotes(filteredNotes);
        }

        // Render notes function
        function renderNotes(notesToRender = notes) {
            notesList.innerHTML = '';
            
            notesToRender.forEach(note => {
                const noteElement = document.createElement('div');
                noteElement.className = 'bg-white p-4 rounded shadow flex flex-col gap-2';
                
                const tagsHTML = note.tags.map(tag => 
                    `<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">${tag}</span>`
                ).join('');

                const audioHTML = note.audio ? 
                    `<audio controls class="w-full">
                        <source src="${note.audio}" />
                        Browser kamu tidak mendukung audio
                    </audio>` : '';

                noteElement.innerHTML = `
                    <div class="flex justify-between items-center">
                        <h2 class="font-bold text-xl">${note.title}</h2>
                        <button
                            onclick="deleteNote(${note.id})"
                            class="text-red-500 hover:underline"
                        >
                            Hapus
                        </button>
                    </div>
                    <p>${note.content}</p>
                    ${audioHTML}
                    <div class="flex gap-2 text-sm text-gray-600">
                        ${tagsHTML}
                    </div>
                `;
                
                notesList.appendChild(noteElement);
            });
        }

        // Event listeners
        noteForm.addEventListener('submit', addNote);
        filterInput.addEventListener('input', filterNotes);

        // Initial render
        renderNotes();
    </script>
</body>
</html>