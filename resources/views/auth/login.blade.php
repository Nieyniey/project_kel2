@extends('layouts.main')

@section('title', 'Login Page')

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

    {{-- LEFT PANEL --}}
    <div style="
        width: 45%;
        background: #FFFBE8;
        padding: 60px 70px;
    ">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom: 30px;">
            <img src="{{ asset('logo.jpg') }}" 
                 alt="WTS Logo"
                 style="height: 160px;">
        </div>

        <h3 style="font-weight: 700; margin-bottom: 25px;">Log In</h3>

        {{-- FORM --}}
        <form>

            {{-- EMAIL --}}
            <label>Email Address</label>
            <input type="email" placeholder="Enter Email Address"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                ">

            {{-- PASSWORD --}}
            <label>Password</label>
            <input type="password" placeholder="Enter Password"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:10px;
                ">
            
            {{-- FORGOT --}}
            <a href="#" style="color:#FF6E00; font-size:14px; text-decoration:none;">
                Forgot password?
            </a>

            {{-- LOGIN BTN --}}
            <button style="
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

        {{-- SIGNUP LINK --}}
        <div style="text-align:center; margin-top:20px;">
            <p>Don’t have an account?</p>

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

    {{-- RIGHT GALLERY --}}
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
