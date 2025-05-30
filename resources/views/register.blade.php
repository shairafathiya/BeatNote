<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
     <div class="min-h-screen flex items-center justify-center bg-[#f5ecda] text-[#3b362f]">
      <div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-sm">
        <h1 class="text-2xl font-bold mb-6 text-center">Register to BeatsNotes</h1>
        <form>
          <div class="mb-4">
            <label class="block mb-1">Username</label>
            <input
              type="text"
              class="w-full px-3 py-2 border rounded-md"
              placeholder="your username"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input
              type="email"
              class="w-full px-3 py-2 border rounded-md"
              placeholder="your@email.com"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input
              type="password"
              class="w-full px-3 py-2 border rounded-md"
              placeholder="********"
            />
          </div>
          <div class="mb-6">
            <label class="block mb-1">Confirm Password</label>
            <input
              type="password"
              class="w-full px-3 py-2 border rounded-md"
              placeholder="********"
            />
          </div>
          <button
            type="submit"
            class="w-full bg-[#5f3d3d] text-white py-2 rounded-md hover:bg-[#472d2d]"
            onClick={handleSubmit}
          >
            Register
          </button>
        </form>
      </div>
    </div>
</body>
</html>