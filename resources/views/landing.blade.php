<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-screen bg-[#f5ecda] text-[#3b362f]">
    
        <nav class="bg-[#716c60] text-white flex justify-between items-center px-6 py-4 font-serif text-xl">
            <div class="space-x-4">
                <a href="#" class="hover:underline">Home</a>
                <a href="#" class="hover:underline">Notes</a>
                <a href="#" class="hover:underline">Events</a>
            </div>
            <div class="text-white">Username</div>
        </nav>

        <div class="flex flex-col items-center justify-center p-6 text-center">
            <div class="flex items-center gap-4 mt-10">
                <h1 class="text-[100px] font-bold tracking-widest">
                    <img src="/image/disc.svg"  class="w-35 h-45 inline-block" />
                    BEAT<br /><span class="text-[120px]">NOTES</span>
                </h1>
            </div>

            <h6 class="text-[35px] font-light font-serif">Welcome to our project!</h6>
            <p class="text-[20px] font-serif">A platform for musicians to document their notes and events.</p>
            <p class="text-[20px] font-serif">Join us in spectacular world of melody creativity!</p>
            <p class="text-[20px] font-serif">Let's make music together!</p>

            <div class="flex gap-10 mt-16">
                <div class="bg-[#8a7d6c] p-6 rounded-xl text-white flex flex-col items-center w-40">
                    <a href="../register" class="hover:underline text-lg font-serif">Register</a>
                </div>
                <div class="bg-[#b37840] p-6 rounded-xl text-white flex flex-col items-center w-40">
                    <a href="/login" class="hover:underline text-lg font-serif">Login</a>
                </div>
            </div>
        </div>

        <footer class="p-4 bg-black items-center gap-4 mt-10">
            <div class="flex justify-center text-white text-sm font-serif">
                <p>Made by Shaira Fathiya & Nur Shadiqah</p>
            </div>
            <div class="flex justify-center text-white text-sm font-serif">
                <p>@2025</p>
            </div>
        </footer>
    </div>
</body>
</html>
