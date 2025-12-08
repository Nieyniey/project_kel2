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

    {{-- <!-- LEFT IMAGES -->
    <div class="grid-box">
        <img src="/img/item1.jpg" class="grid-img">
        <img src="/img/item2.jpg" class="grid-img">
        <img src="/img/item3.jpg" class="grid-img">
        <img src="/img/item4.jpg" class="grid-img">
    </div> --}}

    <!-- CARD -->
    <div class="card-box">

        <h2 style="font-weight:700; font-size:20px; color:#333; margin-bottom:6px;">
            Reset Sandi
        </h2>

        <p style="font-size:14px; color:#555; margin-bottom:20px;">
            Masukkan alamat email Anda
        </p>

        <form method="POST" action="{{ route('forgot.send') }}">
            @csrf

            <input type="email" name="email"
                placeholder="Masukan Alamat Email"
                style="
                    width:100%;
                    padding:10px 12px;
                    border:2px solid #FF8A3D;
                    border-radius:8px;
                    margin-bottom:18px;
                    font-size:14px;
                " required>

            <button type="submit"
                style="
                    background:#FF6E00;
                    color:white;
                    width:100%;
                    padding:10px;
                    border:none;
                    border-radius:8px;
                    cursor:pointer;
                    font-size:15px;
                    font-weight:600;
                ">
                kirim
            </button>
        </form>

        <div style="margin-top:12px; text-align:center;">
            <a href="/login" style="color:#FF6E00; font-size:14px; text-decoration:none;">
                Kembali ke halaman login
            </a>
        </div>

    </div>

    {{-- <!-- RIGHT IMAGES -->
    <div class="grid-box">
        <img src="/img/item5.jpg" class="grid-img">
        <img src="/img/item6.jpg" class="grid-img">
        <img src="/img/item7.jpg" class="grid-img">
        <img src="/img/item8.jpg" class="grid-img">
    </div> --}}

</div>

@endsection
