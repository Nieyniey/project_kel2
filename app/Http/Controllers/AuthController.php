<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Show Signup Page
    public function showSignup()
    {
        return view('auth.signUp');
    }

    // REGISTER (SIGN UP)
    public function signup(Request $request)
    {
        // 1. VALIDATION
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // 2. CREATE USER
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, 
        ]);

        // 3. REDIRECT TO LOGIN PAGE
        return redirect()->route('login')->with('berhasil', 'Akun berhasil dibuat!');
    }

    // LOGIN
    public function login(Request $request)
    {
        // 1. VALIDATION
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. ATTEMPT LOGIN
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // REGENERATE SESSION
            $request->session()->regenerate();

            return redirect('/home');
        }

        // LOGIN FAILED
        return back()->withErrors([
            'email' => 'Email atau sandi salah.',
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
