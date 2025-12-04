@extends('layouts.app')

@section('title', 'Buyer Home')

@section('content')

{{-- HEADER --}}
<div style="
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    background:#faf6ec; 
    padding:12px 18px;
    border-bottom:1px solid #d8d3c4;
">
    <div style="display:flex; align-items:center; gap:15px;">
        <a href="{{ route('buyer.settings') }}">
            <img src="/icons/gear.png" style="width:22px; cursor:pointer;">
        </a>
    </div>

    <div style="font-size:24px; font-weight:700;">WTS</div>

    <div style="display:flex; align-items:center; gap:15px;">
        <img src="/icons/search.png" 
             style="width:20px; cursor:pointer;" 
             onclick="toggleSearchBar()">

        <a href="{{ route('buyer.favorites') }}">
            <img src="/icons/heart.png" style="width:20px;">
        </a>

        <a href="{{ route('chat.index') }}">
            <img src="/icons/chat.png" style="width:20px;">
        </a>

        <a href="{{ route('buyer.cart') }}">
            <img src="/icons/bag.png" style="width:20px;">
        </a>
    </div>
</div>

{{-- SEARCH BAR --}}
<div id="searchBar" style="
    display:none;
    background:#fff;
    padding:10px 18px;
    border-bottom:1px solid #ccc;
">
    <input type="text" placeholder="Search items..." style="
        width:100%; 
        padding:10px; 
        border:1px solid #cfcfcf;
        border-radius:6px;
    ">
</div>

{{-- CATEGORY NAV --}}
<div style="
    display:flex; 
    justify-content:space-around;
    background:#f6f2e7;
    padding:10px;
    border-bottom:1px solid #ddd;
">
    <div style="text-align:center; width:70px;">
        <img src="/icons/bagcat.png" style="width:35px;">
        <div style="font-size:12px;">Outer</div>
    </div>
    <div style="text-align:center; width:70px;">
        <img src="/icons/dresscat.png" style="width:35px;">
        <div style="font-size:12px;">Dress</div>
    </div>
    <div style="text-align:center; width:70px;">
        <img src="/icons/pantscat.png" style="width:35px;">
        <div style="font-size:12px;">Pants</div>
    </div>
    <div style="text-align:center; width:70px;">
        <img src="/icons/shoescat.png" style="width:35px;">
        <div style="font-size:12px;">Shoes</div>
    </div>
    <div style="text-align:center; width:70px;">
        <img src="/icons/accessories.png" style="width:35px;">
        <div style="font-size:12px;">Accessories</div>
    </div>
</div>

{{-- BANNER --}}
<div style="padding:18px;">
    <img src="/images/banner1.png" style="width:100%; border-radius:10px;">
</div>

{{-- PRODUCT GRID --}}
<div style="
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(150px, 1fr));
    gap:18px;
    padding:18px;
">
    @foreach ($products as $product)
        <div style="
            background:#faf5e5;
            border-radius:10px;
            padding:10px; 
            border:1px solid #e5dccb;
        ">
            <a href="{{ route('products.show', $product->product_id) }}">
                <img src="{{ $product->image }}" style="width:100%; border-radius:10px;">
            </a>

            <div style="margin-top:8px; font-weight:600; font-size:14px;">
                {{ $product->name }}
            </div>

            <div style="font-size:13px; color:#a46536;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
        </div>
    @endforeach
</div>

<script>
function toggleSearchBar() {
    let bar = document.getElementById("searchBar");
    bar.style.display = (bar.style.display === "none" || bar.style.display === "") 
                        ? "block" : "none";
}
</script>

@endsection
