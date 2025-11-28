@extends('layouts.main')

@section('title', 'Forget Password')

@section('content')

<div style="
    display: flex;
    max-width: 1300px;
    margin: 40px auto;
    border-radius: 25px;
    overflow: hidden;
    background: #FFFBE8;
">

    {{-- LEFT GALLERY --}}
    <div style="
        width: 32%;
        padding: 15px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    ">
        @foreach (['item1','item2','item3','item4'] as $img)
            <img src="{{ asset($img.'.jpg') }}"
                 style="width:100%; height:200px; object-fit:cover; border-radius:12px;">
        @endforeach
    </div>

    {{-- CENTER --}}
    <div style="
        width: 36%;
        background: #FFFBE8;
        padding: 60px 50px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        text-align:center;
    ">

        {{-- LOGO (optional) --}}
        <img src="{{ asset('logo.jpg') }}"
             style="height:120px; margin-bottom:25px;">

        <h2 style="font-weight:700; margin-bottom:20px; font-size:32px; color:#CC5A1A;">
            Forget <br> Password
        </h2>

        <label style="font-weight:600; margin-bottom:8px;">Enter Email Address</label>

        <input type="email" placeholder="Email Address"
            style="
                width:100%;
                padding:12px 15px;
                border-radius:10px;
                border:2px solid #FF8A3D;
                margin-bottom:25px;
                font-size:15px;
            ">

        <a href="/forgot-password/verify"
            style="
                padding:12px 35px;
                border-radius:12px;
                background:#FF6E00;
                color:white;
                text-decoration:none;
                font-size:17px;
                margin-bottom:18px;
            ">
            Send
        </a>

        <a href="/login" style="color:#FF6E00; font-size:15px; text-decoration:none;">
            Back to log in
        </a>

    </div>

    {{-- RIGHT GALLERY --}}
    <div style="
        width: 32%;
        padding: 15px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    ">
        @foreach (['item5','item6','item7','item8'] as $img)
            <img src="{{ asset($img.'.jpg') }}"
                 style="width:100%; height:200px; object-fit:cover; border-radius:12px;">
        @endforeach
    </div>

</div>

@endsection
