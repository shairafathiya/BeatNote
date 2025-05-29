{{-- resources/views/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login to BeatsNotes</title>
    @vite('resources/css/app.css') {{-- Jika pakai Vite --}}
</head>
<body class="min-h-screen flex items-center justify-center bg-[#f5ecda] text-[#3b362f]">
    <div class="bg-white p-10 rounded-xl shadow-xl w-auto 5 max-w-sm">
        <h1 class="text-2xl font-bold mb-6 text-center">Login to BeatsNotes</h1>
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1" for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full px-3 py-2 border rounded-md"
                    placeholder="your@email.com"
                    required
                />
            </div>
            <div class="mb-6">
                <label class="block mb-1" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-3 py-2 border rounded-md"
                    placeholder="********"
                    required
                />
            </div>
            <button
                type="submit"
                class="w-full bg-[#5f3d3d] text-white py-2 rounded-md hover:bg-[#472d2d]"
            >
                Login
            </button>
        </form>
    </div>
</body>
</html>
