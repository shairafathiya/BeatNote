<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Ambil credentials
        $credentials = $request->only('email', 'password');

        // Autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect ke landing page setelah login berhasil
            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect_url' => 'home'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }
}