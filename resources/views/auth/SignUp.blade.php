@extends('layouts.main')

@section('title', 'Sign Up')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    align-items: stretch;
    max-width: 1400px;
    margin: 20px auto;
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
            <img src="{{ asset('logo.jpg') }}" 
                 alt="WTS Logo"
                 style="height: 160px;">
        </div>

        {{-- FORM --}}
        <h3 style="font-weight: 700; margin-bottom: 25px;">Sign Up</h3>

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
            <input type="password" placeholder="Password must consist of 8 characters"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:18px;
                ">

            {{-- CONFIRM --}}
            <label>Confirm Password</label>
            <input type="password" placeholder="Enter Password"
                style="
                    width:100%;
                    padding:10px 15px;
                    border:2px solid #FF8A3D;
                    border-radius:10px;
                    margin-bottom:25px;
                ">

            {{-- SIGN IN BUTTON --}}
            <button style="
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
                Sign in
            </button>

        </form>

        <div style="text-align:center;">
            <p>Already have an account?</p>

            <a href="/login" style="
                padding:8px 30px; 
                border:2px solid #FF6E00;
                color:#FF6E00;
                background:white;
                border-radius:10px;
                text-decoration:none;
            ">Log in</a>
        </div>

        <p style="margin-top:22px; font-size:14px; text-align:center;">
            By signing up, you agree to our
            <a href="#" style="font-weight:bold; color:#000;">Terms & Regulation</a>
        </p>

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
