<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Sementara, langsung redirect ke welcome (belum cek login)
        return redirect('/welcome');
    }
}
