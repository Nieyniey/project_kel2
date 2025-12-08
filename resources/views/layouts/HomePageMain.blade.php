@extends('layouts.main')

@section('title', 'Home Page')

@section('content')
<div style="
    background: #FFFBE8; 
    border-radius: 20px; 
    padding: 60px; 
    text-align:center;
">

    {{-- Logo WTS --}}
    <img src="{{ asset('img/Logo.jpg') }}" 
         alt="WTS Logo"
         style="height: 150px; margin-bottom: 20px;">

    {{-- Description --}}
    <p style="max-width: 820px; margin: 0 auto; font-size: 17px; color: #6b6b6b; line-height: 1.6;">
        Dengan WantToSell, kami bertujuan untuk menyediakan barang-barang terjangkau dalam berbagai desain.
        Di WantToSell, Anda dapat menemukan barang-barang yang benar-benar sesuai dengan daftar keinginan Anda.
        Dengan membeli barang bekas, kami tidak hanya membantu memperlambat produksi industri
        tetapi juga mendorong ekonomi sirkular—berkontribusi pada peningkatan
        kemakmuran ekonomi bangsa kita secara keseluruhan dan kesejahteraan lingkungan global kita.
    </p>

    {{-- Image Gallery --}}
    <div style="display: flex; justify-content:center; gap: 25px; margin-top: 50px;">
        <img src="{{ asset('img/item1.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('img/item2.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('img/item3.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('img/item4.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('img/item5.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
    </div>

    {{-- CTA Buttons --}}
    <div style="margin-top: 40px;">
        <a href="/login" 
           style="padding: 10px 35px; background:#FF6E00; color:white; border-radius:50px; margin-right:10px; text-decoration:none;">
           Sign In
        </a>

        <a href="/signup" 
           style="padding: 10px 35px; background:white; color:#FF6E00; border:2px solid #FF6E00; border-radius:50px; text-decoration:none;">
           Sign Up
        </a>
    </div>

</div>
@endsection
