<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // STEP 1: show email form
    public function showEmail()
    {
        return view('auth.forgetpass1');
    }

    // STEP 1: validate email
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // go to new password page
        return redirect()->route('reset.page', $request->email);
    }

    // STEP 2: show new password form
    public function showReset($email)
    {
        return view('auth.forgetpass3', compact('email'));
    }

    // STEP 2: save new password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('berhasil', 'Sandi berhasil diperbarui!');
    }
}
