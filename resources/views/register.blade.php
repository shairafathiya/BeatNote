<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - BeatsNotes</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-[#f5ecda] text-[#3b362f]">
        <div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-sm">
            <h1 class="text-2xl font-bold mb-6 text-center">Register to BeatsNotes</h1>
            
            <!-- Message display -->
            <div id="message" class="mb-4 p-3 rounded-md hidden"></div>
            
            <form id="registerForm">
                <div class="mb-4">
                    <label for="name" class="block mb-1 font-medium">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5f3d3d] focus:border-transparent"
                        placeholder="Your full name"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label for="email" class="block mb-1 font-medium">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5f3d3d] focus:border-transparent"
                        placeholder="your@email.com"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-1 font-medium">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5f3d3d] focus:border-transparent"
                        placeholder="********"
                        required
                        minlength="8"
                    />
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-1 font-medium">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5f3d3d] focus:border-transparent"
                        placeholder="********"
                        required
                        minlength="8"
                    />
                </div>
                <button
                    type="submit"
                    id="registerBtn"
                    class="w-full bg-[#5f3d3d] text-white py-2 rounded-md hover:bg-[#472d2d] transition-colors duration-200 font-medium"
                >
                    Register
                </button>
            </form>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="/login" class="text-[#5f3d3d] hover:underline font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const button = document.getElementById('registerBtn');
            
            // Basic validation
            if (password !== password_confirmation) {
                showMessage('Passwords do not match!', 'error');
                return;
            }
            
            if (password.length < 8) {
                showMessage('Password must be at least 8 characters long!', 'error');
                return;
            }
            
            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Registering...';
            
            // Prepare data for Laravel
            const data = {
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation
            };
            
            console.log('Sending registration data:', data);
            
            // Send to Laravel backend
            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    showMessage(data.message || 'Registration successful! Redirecting to login...', 'success');
                    
                    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/login';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Registration failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Re-enable button
                button.disabled = false;
                button.textContent = 'Register';
            });
        });
        
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = `mb-4 p-3 rounded-md ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'}`;
            messageDiv.classList.remove('hidden');
            
            // Hide message after 5 seconds
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 5000);
        }
    </script>
</body>
</html>