@extends('layouts.app')

@section('title', 'Notes')

@section('content')
<div class="bg-[#faf6ee] flex flex-col">
    <div class="p-8 max-w-4xl mx-auto space-y-8 text-[#3b362f] bg-[#faf6ee] min-h-screen">
        <h1 class="text-3xl font-bold">🎶 Notes</h1>

        <!-- FORM -->
        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input
                    type="text"
                    name="title"
                    placeholder="Judul"
                    class="w-full border p-2 rounded"
                    value="{{ old('title') }}"
                    required
                />
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <textarea
                    name="content"
                    placeholder="Isi catatan"
                    class="w-full border p-2 rounded h-32"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div>
                    <label class="block mb-1">Upload Audio (opsional)</label>
                    <input 
                        class="text-amber-800 hover:underline" 
                        type="file" 
                        name="audio_file"
                        accept="audio/*"
                    />
                    @error('audio_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach(['lirik', 'chord', 'ide', 'draft', 'selesai'] as $tag)
                        <label class="flex items-center space-x-1">
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag }}"
                                {{ in_array($tag, old('tags', [])) ? 'checked' : '' }}
                            />
                            <span class="text-sm capitalize">{{ $tag }}</span>
                        </label>
                    @endforeach
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="bg-[#5f3d3d] text-white px-4 py-2 rounded hover:bg-[#472d2d] transition-colors"
                >
                    Tambah Catatan
                </button>
            </form>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any() && !$errors->has('title') && !$errors->has('content') && !$errors->has('audio_file') && !$errors->has('tags'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FILTER -->
        <div>
            <form method="GET" action="{{ route('notes.index') }}">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari judul..."
                    class="w-full border p-2 rounded"
                    value="{{ request('search') }}"
                    onchange="this.form.submit()"
                />
            </form>
        </div>

        <!-- LIST NOTES -->
        <div class="space-y-4">
            @forelse($notes as $note)
                <div class="bg-white p-4 rounded shadow flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <h2 class="font-bold text-xl">{{ $note->title }}</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('notes.edit', $note->id) }}" 
                               class="text-blue-600 hover:underline">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('notes.destroy', $note->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <p class="whitespace-pre-wrap">{{ $note->content }}</p>
                    
                    @if($note->audio_path)
                        <audio controls class="w-full">
                            <source src="{{ Storage::url($note->audio_path) }}" type="audio/mpeg">
                            <source src="{{ Storage::url($note->audio_path) }}" type="audio/wav">
                            <source src="{{ Storage::url($note->audio_path) }}" type="audio/ogg">
                            Browser kamu tidak mendukung audio
                        </audio>
                    @endif
                    
                    @if($note->tags && count($note->tags) > 0)
                        <div class="flex gap-2 text-sm text-gray-600">
                            @foreach($note->tags as $tag)
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-xs text-gray-500 mt-2">
                        Dibuat: {{ $note->created_at->format('d M Y H:i') }}
                        @if($note->updated_at != $note->created_at)
                            | Diupdate: {{ $note->updated_at->format('d M Y H:i') }}
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded shadow text-center text-gray-500">
                    <p>Belum ada catatan yang ditambahkan.</p>
                    <p class="mt-2">Mulai menulis ide musik Anda!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6">
                {{ $notes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection