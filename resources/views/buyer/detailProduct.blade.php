@extends('layouts.app')

@section('content')
<div style="background:#f0f0f0; padding:20px;">
    
    <!-- Header -->
    <div style="background:white; padding:15px; border-radius:6px; display:flex; align-items:center;">
        <a href="{{ url()->previous() }}" style="text-decoration:none; color:#ff6f00; font-size:18px; margin-right:10px;">←</a>
        <span style="font-weight:bold; color:#ff6f00;">Detail Product</span>
    </div>

    <!-- Main Product Card -->
    <div style="background:white; margin-top:15px; padding:20px; border-radius:15px; display:flex; gap:20px;">
        
        <!-- Left Image -->
        <img src="{{ $product->image }}" 
             style="width:350px; height:350px; border-radius:10px; object-fit:cover;">

        <!-- Right Info -->
        <div style="flex:1;">
            <h2 style="font-size:20px; font-weight:bold; margin-bottom:5px;">
                {{ $product->name }}
            </h2>

            <!-- Seller -->
            <div style="display:flex; align-items:center; margin-bottom:10px;">
                <img src="/images/profile.png" style="width:35px; height:35px; border-radius:50%; margin-right:10px;">
                <div>
                    <span style="font-weight:bold; font-size:14px;">{{ $product->seller }}</span><br>
                    <span style="font-size:12px; color:#666;">
                        ⭐ {{ $product->rating }} ({{ $product->reviews }})
                    </span>
                </div>

                <button style="margin-left:auto; padding:6px 12px; background:#ff8f00; border:none; border-radius:6px; color:white;">
                    Chat
                </button>
            </div>

            <!-- Description -->
            <p style="margin-top:10px; color:#444;">{{ $product->description }}</p>

            <!-- Price -->
            <div style="font-size:20px; font-weight:bold; margin-top:15px; color:#ff6f00;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <!-- Quantity + Add Cart -->
            <div style="display:flex; align-items:center; margin-top:15px; gap:10px;">
                <button style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">-</button>
                <span>1</span>
                <button style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">+</button>

                <button style="background:#ff6f00; padding:8px 18px; border:none; color:white; border-radius:6px;">
                    Add
                </button>

                <button style="background:none; border:none; font-size:18px; color:#ff6f00;">
                    ♥
                </button>
            </div>

        </div>
    </div>

    <!-- Similar Products -->
    <h3 style="margin-top:25px; color:#ff6f00; font-weight:bold;">Similar Products</h3>

    <div style="background:white; padding:20px; border-radius:15px; margin-top:10px;">
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
            
            @foreach($similarProducts as $similar)
            <a href="{{ route('products.show', $similar->product_id) }}" 
                style="text-decoration:none; color:black;">

                <div style="background:#f5edd1; padding:15px; border-radius:10px; text-align:center;">

                    <img src="{{ $similar->image_path }}" 
                        style="width:160px; height:160px; object-fit:cover; border-radius:8px;">

                    <div style="margin-top:8px; font-weight:bold;">
                        {{ $similar->name }}
                    </div>

                    <div style="color:#ff6f00; margin-top:5px;">
                        Rp {{ number_format($similar->price, 0, ',', '.') }}
                    </div>

                </div>

            </a>
            @endforeach

        </div>
    </div>

</div>
@endsection
