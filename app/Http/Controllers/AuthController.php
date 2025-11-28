<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showSignup()
    {
        return view('auth.signup');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLogout()
    {
        return view('auth.logout');
    }

}
