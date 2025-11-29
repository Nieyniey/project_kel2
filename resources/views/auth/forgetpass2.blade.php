@extends('layouts.main')

@section('title', 'Verification')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    max-width: 1300px;
    margin: 30px auto;
    border-radius: 25px;
    overflow: hidden;
    background:#FFFBE8;
">

    {{-- LEFT GALLERY --}}
    <div style="
        width: 30%;
        padding: 15px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    ">
        <img src="{{ asset('item1.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item2.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item3.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item4.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
    </div>

    {{-- CENTER CARD --}}
    <div style="
        width: 40%;
        padding: 50px 60px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        background:#FFF7DE;
    ">
        <h2 style="font-weight:700; margin-bottom:20px; text-align:center;">
            Verification
        </h2>

        <p style="margin-bottom:25px; text-align:center; color:#444;">
            Check your email and enter the verification code
        </p>

        {{-- OTP INPUT --}}
        <div style="display:flex; gap:15px; margin-bottom:30px;">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1"
                    style="
                        width:45px; height:45px;
                        text-align:center;
                        border-radius:8px;
                        border:2px solid #FF8A3D;
                        font-size:20px;
                        background:#FFFBE8;
                    ">
            @endfor
        </div>

        {{-- BUTTON --}}
        <a href="/forgot-password/new"
            style="
                padding:10px 35px;
                border-radius:10px;
                background:#FF6E00;
                color:white;
                text-decoration:none;
                margin-bottom:12px;
                font-size:16px;
            ">
            Send
        </a>

        <a href="#" style="color:#FF6E00; text-decoration:none;">Resend</a>

    </div>

    {{-- RIGHT GALLERY --}}
    <div style="
        width: 30%;
        padding: 15px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    ">
        <img src="{{ asset('item5.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item6.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item7.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item8.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
    </div>

</div>

@endsection
