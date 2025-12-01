@extends('layouts.app')

@section('content')
<div style="padding: 20px">

    <h2 style="font-size:24px; font-weight:bold; margin-bottom:20px;">Favorite Products</h2>

    @if($favoriteProducts->count() == 0)
        <p>You have no favorite products yet.</p>
    @else

    <div style="
        display:grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap:20px;
    ">
        @foreach($favoriteProducts as $product)
        <div style="
            background:#FFF5DC;
            border-radius:10px;
            padding:15px;
            text-align:center;
        ">
            <img src="{{ $product->image }}" alt="" style="
                width:100%;
                height:150px;
                object-fit:cover;
                border-radius:8px;
            ">

            <p style="margin-top:10px; font-weight:bold;">
                {{ $product->name }}
            </p>

            <p style="color:#E57C23; font-weight:bold; margin:5px 0;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <div style="display:flex; justify-content:center; gap:10px; margin-top:10px;">
                <a href="{{ route('detailProduct', $product->id) }}" 
                   style="font-size:14px; padding:6px 12px; background:#FD9B41; color:white; border-radius:6px;">
                   View
                </a>

                <form action="{{ route('toggleFavorite', $product->id) }}" method="POST">
                    @csrf
                    <button style="background:none; border:none; font-size:20px; cursor:pointer; color:#E32636;">
                        ♥
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @endif

</div>
@endsection
