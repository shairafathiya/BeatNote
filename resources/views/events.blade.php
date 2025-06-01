<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Timeline</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#f9f4e8',
                        'primary-text': '#3b362f',
                        'button-bg': '#5f3d3d',
                        'button-hover': '#472d2d'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-primary-bg text-primary-text min-h-screen">
    <div class="flex flex-col p-8 mx-auto space-y-8">
        <h1 class="text-3xl font-bold mb-4">📅 Event Timeline</h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Event Form -->
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-xl font-bold">➕ Tambah Event</h2>
            
            <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Judul Event"
                    class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-button-bg"
                    required
                />
                
                <input
                    type="date"
                    name="date"
                    value="{{ old('date') }}"
                    class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-button-bg"
                    required
                />
                
                <select
                    name="type"
                    class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-button-bg"
                    required
                >
                    <option value="Manggung" {{ old('type') == 'Manggung' ? 'selected' : '' }}>Manggung</option>
                    <option value="Latihan" {{ old('type') == 'Latihan' ? 'selected' : '' }}>Latihan</option>
                    <option value="Recording" {{ old('type') == 'Recording' ? 'selected' : '' }}>Recording</option>
                </select>
                
                <button
                    type="submit"
                    class="bg-button-bg text-white px-4 py-2 rounded hover:bg-button-hover transition-colors duration-200"
                >
                    Simpan Event
                </button>
            </form>
        </div>

        <!-- Events Timeline -->
        <div class="space-y-4">
            <h2 class="text-xl font-bold">📌 Timeline Events</h2>
            
            @if($events->count() > 0)
                <ul class="space-y-4">
                    @foreach($events as $event)
                        <li class="bg-white p-4 rounded shadow border-l-4 relative group" 
                            style="border-color: {{ $event->border_color }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-grow">
                                    <div class="font-semibold text-lg">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $event->date->format('d/m/Y') }} • {{ $event->type }}
                                    </div>
                                </div>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('events.destroy', $event) }}" method="POST" 
                                      class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                    <p>Belum ada event yang ditambahkan.</p>
                    <p class="text-sm mt-2">Tambahkan event pertama Anda menggunakan form di atas!</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>