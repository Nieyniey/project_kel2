<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsSellerMiddleware
{
    /**
     * Handle an incoming request.
     */

  
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum, redirect ke halaman login
            return redirect()->route('login');
        }

        // 2. Cek apakah pengguna yang login memiliki relasi 'seller'
        if (Auth::user()->seller) {
            // Lolos: Lanjutkan request ke controller
            return $next($request);
        }

        // 3. Gagal: Redirect ke homepage dengan pesan error
        return redirect('/')->with('error', 'Akses ditolak. Halaman ini hanya untuk Seller.');
    }

}
