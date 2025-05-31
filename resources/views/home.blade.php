<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beat Notes - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cream': '#f5ecda',
                        'brown-dark': '#3b362f',
                        'brown-nav': '#716c60',
                        'brown-medium': '#8a7d6c',
                        'brown-red': '#5f3d3d',
                        'brown-light': '#b37840'
                    },
                    fontFamily: {
                        'serif': ['Times New Roman', 'serif']
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="min-h-screen bg-cream text-brown-dark">
        <!-- Navbar -->
        <nav class="bg-brown-nav text-white flex justify-between items-center px-6 py-4 font-serif text-xl">
            <div class="space-x-4">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <a href="{{ route('notes.index') }}" class="hover:underline">Notes</a>
                <a href="{{ route('events') }}" class="hover:underline">Events</a>
            </div>
            <div class="text-white">Shaira</div>
        </nav>

        <!-- Main Section -->
        <div class="flex flex-col items-center justify-center p-6 text-center">
            <div class="flex items-center gap-4 mt-10">
                <h1 class="text-[100px] font-bold tracking-widest">
                   <img src="/image/disc.svg"  class="w-20 h-25 inline-block" />
                    BEAT<br />
                    <span class="text-[120px]">NOTES</span>
                </h1>
            </div>
            
            <h6 class="text-[35px] font-light font-serif">Welcome to our project!</h6>
            <p class="text-[20px] font-serif">A platform for musicians to document their notes and events.</p>
            <p class="text-[20px] font-serif">Let's make music together!</p>
            
            <!-- Icons Section -->
            <div class="flex gap-10 mt-16">
                <div class="bg-brown-medium p-6 rounded-xl text-white flex flex-col items-center w-40">
                    <div class="text-4xl">🎵</div>
                    <a href="{{ route('notes.index') }}" class="hover:underline text-lg font-serif">Notes</a>
                </div>
                
                <div class="bg-brown-red p-6 rounded-xl text-white flex flex-col items-center w-40">
                    <div class="text-4xl">💿</div>
                    <a href="{{ route('music') }}" class="hover:underline text-lg font-serif">Music</a>
                </div>
                
                <div class="bg-brown-light p-6 rounded-xl text-white flex flex-col items-center w-40">
                    <div class="text-4xl">✴️</div>
                    <a href="{{ route('events') }}" class="hover:underline text-lg font-serif">Event</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="p-4 bg-black items-center gap-4 mt-10">
            <div class="flex justify-center text-white text-sm font-serif">
                <p>Made by Shaira fathiya & Nur Shadiqah</p>
            </div>
            <div class="flex justify-center text-white text-sm font-serif">
                <p>@2025</p>
            </div>
        </footer>
    </div>
</body>
</html>