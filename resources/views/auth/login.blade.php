@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    align-items: stretch;
    max-width: 1400px;
    margin: 20px auto;
    border-radius: 25px;
    overflow: hidden;
    background: #FFFBE8;
">

    <div style="
        width: 45%;
        background: #FFFBE8;
        padding: 60px 70px;
    ">

        <div style="text-align:center; margin-bottom: 30px;">
            <img src="{{ asset('logo.jpg') }}" 
                 alt="WTS Logo"
                 style="height: 160px;">
        </div>

        <h3 style="font-weight: 700; margin-bottom: 25px;">Log In</h3>

        {{-- ERROR MESSAGE --}}
        @if ($errors->any())
            <div style="
                background:#ffe0e0;
                color:#b10000;
                padding:10px 15px;
                border-radius:10px;
                margin-bottom:15px;
                border:1px solid #ffb3b3;
                font-size:14px;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- SUCCESS MESSAGE (setelah signup) --}}
        @if (session('success'))
            <div style="
                background:#e0ffe8;
                color:#008f2a;
                padding:10px 15px;
                border-radius:10px;
                margin-bottom:15px;
                border:1px solid #b2ffcc;
                font-size:14px;
            ">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="Masukkan Alamat Email"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                " required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukan Password"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                " required>

            {{-- FORGOT PASSWORD --}}
            <div style="text-align:right; margin-bottom:10px;">
                <a href="/forgot-password" 
                style="color:#FF6E00; font-size:14px; text-decoration:none;">
                    Lupa sandi
                </a>
            </div>

            <button type="submit" style="
                width:100%; 
                background:#FF6E00;
                color:white;
                padding:12px;
                border:none;
                border-radius:10px;
                font-size:16px;
                cursor:pointer;
                margin-top:20px;
            ">
                Log in
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <p>Belum punya akun?</p>
            <a href="/signup" style="
                padding:8px 30px; 
                border:2px solid #FF6E00;
                color:#FF6E00;
                background:white;
                border-radius:10px;
                text-decoration:none;
            ">Sign Up</a>
        </div>
    </div>

    <div style="
        width: 55%;
        background: #FFFBE8;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 12px;
    ">
        <img src="{{ asset('item1.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item2.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item3.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item4.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item5.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item6.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
    </div>

</div>

@endsection
