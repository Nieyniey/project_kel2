@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    align-items: stretch;
    max-width: 1400px;
    border-radius: 25px;
    overflow: hidden;
">

    {{-- LEFT FORM --}}
    <div style="
        background: #FFFBE8; 
        padding: 50px 70px;
        width: 45%;
        min-height: 650px;
    ">
        
        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom: 20px;">
            <img src="{{ asset('img/Logo.jpg') }}" 
                 alt="WTS Logo"
                 style="height: 160px;">
        </div>

        <h3 style="font-weight: 700; margin-bottom: 25px;">Sign Up</h3>

        {{-- FORM --}}
        <form method="POST" action="{{ route('signup.post') }}">
            @csrf

            {{-- NAME --}}
            <label>Nama</label>
            <input type="text" name="name" placeholder="Enter Name"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                " required>

            {{-- EMAIL --}}
            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="Enter Email Address"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                " required>

            {{-- PASSWORD --}}
            <label>Kata Sandi</label>
            <input type="password" name="password" placeholder="Kata Sandi harus terdiri dari 8 karakter"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                " required>

            {{-- CONFIRM --}}
            <label>Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" placeholder="Masukkan Kata Sandi"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:25px;
                " required>

            {{-- SUBMIT --}}
            <button type="submit" style="
                width:100%; 
                background:#FF6E00;
                color:white;
                padding:12px;
                border:none;
                border-radius:10px;
                font-size:16px;
                cursor:pointer;
                margin-bottom:20px;
            ">
                Sign up
            </button>

        </form>

        <div style="text-align:center;">
            <p>Sudah punya akun?</p>
            <a href="/login" style="
                padding:8px 30px; 
                border:2px solid #FF6E00;
                color:#FF6E00;
                background:white;
                border-radius:10px;
                text-decoration:none;
            ">Log in</a>
        </div>
    </div>

    {{-- RIGHT IMAGE --}}
    <div style="
        width: 55%;
        background: #FFFBE8;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 12px;
    ">
        <img src="{{ asset('img/item1.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('img/item2.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('img/item3.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('img/item4.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('img/item5.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('img/item6.jpg') }}" style="width:100%; height:260px; object-fit:cover; border-radius:12px;">
    </div>

</div>

@endsection
