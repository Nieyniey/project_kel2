@extends('layouts.main')

@section('title', 'Sign Up')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    align-items: stretch;
    gap: 0;
    max-width: 1400px;
    margin: 20px auto;
">

    {{-- LEFT FORM --}}
    <div style="
        background: #FFF1D6;
        padding: 50px 70px;
        border-radius: 20px 0 0 20px;
        width: 45%;
        min-height: 650px;
    ">
        
        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom: 20px;">
            <img src="{{ asset('img/logo.jpg') }}" 
                 alt="WTS Logo" 
                 style="height: 140px;">
        </div>

        {{-- FORM --}}
        <h3 style="font-weight: 600; margin-bottom: 20px;">Sign Up</h3>

        <form>

            {{-- EMAIL --}}
            <label>Email Address</label>
            <input type="email" placeholder="Enter Email Address"
                style="
                    width:100%; padding:10px 15px; border:2px solid #FF8A3D;
                    border-radius:8px; margin-bottom:15px;
                ">

            {{-- PASSWORD --}}
            <label>Password</label>
            <input type="password" placeholder="Password must consist of 8 characters"
                style="
                    width:100%; padding:10px 15px; border:2px solid #FF8A3D;
                    border-radius:8px; margin-bottom:15px;
                ">

            {{-- CONFIRM --}}
            <label>Confirm Password</label>
            <input type="password" placeholder="Enter Password"
                style="
                    width:100%; padding:10px 15px; border:2px solid #FF8A3D;
                    border-radius:8px; margin-bottom:25px;
                ">

            {{-- SIGN IN BUTTON --}}
            <button style="
                width:100%; 
                background:#FF6E00;
                color:white;
                padding:12px;
                border:none;
                border-radius:8px;
                font-size:16px;
                cursor:pointer;
            ">
                Sign in
            </button>

        </form>

        <div style="text-align:center; margin-top:15px;">
            <p>Already have an account?</p>

            <a href="/login" style="
                padding:8px 25px; 
                border:2px solid #FF6E00;
                color:#FF6E00;
                background:white;
                border-radius:8px;
                text-decoration:none;
            ">Log in</a>
        </div>

        <p style="margin-top:20px; font-size:14px; text-align:center;">
            By signing up, you are agreeing to our 
            <a href="#" style="font-weight:bold; color:black;">Terms & Regulation</a>
        </p>

    </div>

    {{-- RIGHT GALLERY --}}
    <div style="
        width: 55%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        border-radius: 0 20px 20px 0;
        overflow:hidden;
    ">
        <img src="{{ asset('img/pic1.jpg') }}" style="width:100%; height:200px; object-fit:cover;">
        <img src="{{ asset('img/pic2.jpg') }}" style="width:100%; height:200px; object-fit:cover;">
        <img src="{{ asset('img/pic3.jpg') }}" style="width:100%; height:260px; object-fit:cover;">
        <img src="{{ asset('img/pic4.jpg') }}" style="width:100%; height:260px; object-fit:cover;">
        <img src="{{ asset('img/pic5.jpg') }}" style="width:100%; height:260px; object-fit:cover;">
        <img src="{{ asset('img/pic6.jpg') }}" style="width:100%; height:260px; object-fit:cover;">
    </div>

</div>

@endsection
