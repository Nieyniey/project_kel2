@extends('layouts.main')

@section('title', 'New Password')

@section('content')

<div style="
    display: flex;
    justify-content: center;
    max-width: 1300px;
    margin: 30px auto;
    border-radius: 25px;
    overflow:hidden;
    background:#FFFBE8;
">

    {{-- LEFT GALLERY --}}
    <div style="
        width: 30%;
        padding: 15px;
        display:grid;
        grid-template-columns: repeat(2, 1fr);
        gap:12px;
    ">
        <img src="{{ asset('item1.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item2.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item3.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item4.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
    </div>

    {{-- CENTER CARD --}}
    <div style="
        background:#FFF7DE;
        width: 40%;
        padding: 50px 60px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
    ">
        <h2 style="font-weight:700; margin-bottom:25px;">New Password</h2>

        <label style="width:100%;">Enter New Password</label>
        <input type="password" placeholder="Enter Password"
            style="
                width:100%; padding:10px 15px;
                border-radius:10px; border:2px solid #FF8A3D;
                margin-bottom:20px;
            ">

        <label style="width:100%;">Confirm Password</label>
        <input type="password" placeholder="Confirm Password"
            style="
                width:100%; padding:10px 15px;
                border-radius:10px; border:2px solid #FF8A3D;
                margin-bottom:30px;
            ">

        <a href="/login"
            style="
                padding:10px 35px;
                border-radius:10px;
                background:#FF6E00;
                color:white;
                text-decoration:none;
                font-size:16px;
            ">
            Finished
        </a>
    </div>

    {{-- RIGHT GALLERY --}}
    <div style="
        width: 30%;
        padding: 15px;
        display:grid;
        grid-template-columns: repeat(2, 1fr);
        gap:12px;
    ">
        <img src="{{ asset('item5.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item6.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item7.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
        <img src="{{ asset('item8.jpg') }}" style="width:100%; height:220px; object-fit:cover; border-radius:12px;">
    </div>

</div>

@endsection
