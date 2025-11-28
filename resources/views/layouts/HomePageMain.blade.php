@extends('layouts.main')

@section('title', 'Home Page')

@section('content')
<div style="
    background: #FFFBE8; 
    border-radius: 20px; 
    padding: 60px; 
    text-align:center;
    max-width: 1100px;
    margin: 40px auto;
">

    {{-- Logo WTS --}}
    <img src="{{ asset('logo.jpg') }}" 
         alt="WTS Logo"
         style="height: 150px; margin-bottom: 20px;">

    {{-- Description --}}
    <p style="max-width: 820px; margin: 0 auto; font-size: 17px; color: #6b6b6b; line-height: 1.6;">
        With WantToSell, we aim to provide affordable items in a variety of designs.
        Here on WantToSell, you can find pieces that truly fit your wish list. 
        By purchasing second-hand items, we not only help decelerate industrial production 
        but also encourage a circular economy—contributing to the improvement of our nation’s 
        overall economic prosperity and the well-being of our global environment.
    </p>

    {{-- Image Gallery --}}
    <div style="display: flex; justify-content:center; gap: 25px; margin-top: 50px;">
        <img src="{{ asset('item1.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('item2.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('item3.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('item4.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
        <img src="{{ asset('item5.jpg') }}" style="width:150px; height:180px; border-radius:15px; object-fit:cover;">
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
