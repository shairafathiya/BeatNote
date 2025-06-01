<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BeatsNotes</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-custom { background-color: #f5ecda; }
        .text-custom { color: #3b362f; }
        .bg-button { background-color: #5f3d3d; }
        .bg-button:hover { background-color: #472d2d; }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-custom text-custom">
        <div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-sm">
            <h1 class="text-2xl font-bold mb-6 text-center">Login to BeatsNotes</h1>
            <form onsubmit="handleSubmit(event)">
                <div class="mb-4">
                    <label class="block mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="w-full px-3 py-2 border rounded-md"
                        placeholder="your@email.com"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="w-full px-3 py-2 border rounded-md"
                        placeholder="********"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-button text-white py-2 rounded-md hover:bg-button transition-colors"
                >
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        function handleSubmit(event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData(event.target);
            const email = formData.get('email');
            const password = formData.get('password');
            
            // Basic validation
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Gunakan redirect_url dari response
                    window.location.href = data.redirect_url || '/landing';
                } else {
                    alert(data.message || 'Login failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
                    }
    </script>
</body>
</html>




<!-- fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/landing';
                } else {
                    alert('Login failed: ' + data.message);
                }

            });
            fetch('/login', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ email, password })
            })
        } -->