@extends('layouts.main')

@section('title', 'Logout Page')

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

    {{-- LEFT AREA --}}
    <div style="
        width: 45%;
        background: #FFFBE8;
        padding: 60px 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    ">

        {{-- LOGO --}}
        <img src="{{ asset('logo.jpg') }}" 
             alt="WTS Logo"
             style="height: 160px; margin-bottom: 25px;">

        {{-- MESSAGE --}}
        <h2 style="
            font-weight:700;
            font-size:32px;
            color:#AA4A0F;
            text-align:center;
            line-height: 1.3;
            margin-bottom: 25px;
        ">
            You’ve been logged<br>out
        </h2>

        {{-- LOGIN BUTTON --}}
        <a href="/login" 
           style="
                padding: 12px 40px;
                background:#FF6E00;
                color:white;
                border-radius:40px;
                text-decoration:none;
                font-size:16px;
                margin-bottom: 15px;
           ">
           Log In Again
        </a>

        {{-- HOME LINK --}}
        <a href="/" 
           style="
                font-size:16px;
                color:#FF6E00;
                text-decoration:none;
           ">
           Return to Home
        </a>

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
