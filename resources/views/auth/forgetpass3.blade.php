@extends('layouts.app')

@section('content')

<style>
    body {
        background: #F4F0E6 !important;
    }

    .grid-img {
        width: 180px;
        height: 180px;
        border-radius: 10px;
        object-fit: cover;
    }

    .grid-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        width: 380px;
    }

    .card-box {
        width: 360px;
        background: #FFF7DE;
        padding: 32px 36px;
        border-radius: 16px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
</style>

<div style="
    width:100%;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:70px;
    padding-top:20px;
">

    <!-- LEFT IMAGES -->
    {{-- <div class="grid-box">
        <img src="/item1.jpg" class="grid-img">
        <img src="/item2.jpg" class="grid-img">
        <img src="/item3.jpg" class="grid-img">
        <img src="/item4.jpg" class="grid-img">
    </div> --}}

    <!-- CENTER CARD -->
    <div class="card-box">

        <h2 style="font-size:20px; font-weight:700; color:#333; margin-bottom:10px;">
            Kata Sandi Baru
        </h2>

        <form method="POST" action="{{ route('reset.save') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <label style="width:100%; text-align:left; display:block; font-size:14px;">
                Masukkan Kata Sandi Baru
            </label>
            <input type="password" name="password"
                style="
                    width:100%;
                    padding:10px;
                    border:2px solid #FF8A3D;
                    border-radius:8px;
                    margin-bottom:18px;
                " required>

            <label style="width:100%; text-align:left; display:block; font-size:14px;">
                Konfirmasi Kata Sandi
            </label>
            <input type="password" name="password_confirmation"
                style="
                    width:100%;
                    padding:10px;
                    border:2px solid #FF8A3D;
                    border-radius:8px;
                    margin-bottom:22px;
                " required>

            <button type="submit"
                style="
                    width:100%;
                    background:#FF6E00;
                    color:white;
                    padding:10px;
                    border:none;
                    border-radius:8px;
                    cursor:pointer;
                    font-size:15px;
                    font-weight:600;
                ">
                Selesai
            </button>
        </form>

    </div>

    {{-- <!-- RIGHT IMAGES -->
    <div class="grid-box">
        <img src="/item5.jpg" class="grid-img">
        <img src="/item6.jpg" class="grid-img">
        <img src="/item7.jpg" class="grid-img">
        <img src="/item8.jpg" class="grid-img">
    </div> --}}

</div>

@endsection
