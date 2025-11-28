@extends('layouts.main')

@section('title', 'Logout Page')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    align-items: stretch;
    gap: 0;
    max-width: 1400px;
    margin: 20px auto;
">

    {{-- LEFT AREA --}}
    <div style="
        background: #FFF1D6;
        padding: 50px 70px;
        border-radius: 20px 0 0 20px;
        width: 45%;
        min-height: 650px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    ">

        {{-- LOGO --}}
        <img src="{{ asset('img/logo.jpg') }}" 
             alt="WTS Logo"
             style="height: 150px; margin-bottom: 20px;">

        {{-- MESSAGE --}}
        <h2 style="font-weight:700; font-size:32px; color:#AA4A0F; text-align:center;">
            You’ve been logged <br> out
        </h2>

        {{-- BTN LOGIN --}}
        <a href="/login" 
           style="
                margin-top: 25px;
                padding: 12px 35px;
                background:#FF6E00;
                color:white;
                border-radius:50px;
                text-decoration:none;
                font-size:16px;
           ">
           Log In Again
        </a>

        {{-- HOME LINK --}}
        <a href="/" 
           style="
            margin-top:15px;
            font-size:16px;
            text-decoration:none;
            color:#FF6E00;
           ">
           Return to Home
        </a>

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
