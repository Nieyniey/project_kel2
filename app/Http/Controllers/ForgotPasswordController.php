<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    // email
    public function showEmailForm()
    {
        return view('auth.forgetpass1');
    }

    // Step 1 Submit (fake function)
    public function sendCode(Request $request)
    {
        return redirect()->route('forgot.verify');
    }


    // verify code
    public function showVerifyForm()
    {
        return view('auth.forgetpass2');
    }

    // 
    public function verifyCode(Request $request)
    {
        // validasi otp
        return redirect()->route('forgot.new');
    }


    // new pass
    public function showNewPassword()
    {
        return view('auth.forgetpass3');
    }

    // submit
    public function updatePassword(Request $request)
    {
        // Simulasi update password
        return redirect('/login')->with('success', 'Password has been updated!');
    }
}
