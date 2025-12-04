@extends('layouts.Main') 

@section('title', 'Wishlist')

@section('content')
<div class="container py-5" style="background-color: #f8f8f8;">
    {{-- Header: Back Button and Title --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="text-dark me-3" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left"></i> 
        </a>
        <h2 class="fw-bold mb-0">Wishlist</h2>
    </div>

    {{-- Feedback Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($items->isEmpty())
        <div class="alert alert-info text-center">
            Your wishlist is empty. Start adding products you love!
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach ($items as $item)
            <div class="col">
                <div class="card h-100 shadow-sm border-0" style="background-color: #f7e6d1;">
                    
                    {{-- Product Image / View Detail Link --}}
                    <a href="{{ route('products.show', $item->product->id) }}" class="text-dark text-decoration-none">
                        <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                             class="card-img-top p-3" 
                             alt="{{ $item->product->name }}" 
                             style="height: 150px; object-fit: contain;">
                    </a>
                    
                    <div class="card-body pt-0 pb-2 text-center">
                        {{-- Product Name --}}
                        <p class="card-text fw-bold mb-1">{{ $item->product->name }}</p>
                        
                        {{-- Product Price --}}
                        <h5 class="card-title text-danger fw-bolder">Rp {{ number_format($item->product->price, 0, ',', '.') }}</h5>
                        
                        {{-- Actions (Bag and Heart) --}}
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            
                            {{-- 1. Add to Cart (Bag Icon) --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                <button type="submit" class="btn btn-sm p-2 rounded-circle border-0" 
                                        style="background-color: #e5d8c6;" 
                                        data-bs-toggle="tooltip" title="Add to Cart">
                                    <i class="bi bi-bag"></i>
                                </button>
                            </form>
                            
                            {{-- 2. Remove from Wishlist (Filled Heart Icon) --}}
                            <form action="{{ route('wishlist.remove') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                <button type="submit" class="btn btn-sm p-2 rounded-circle border-0" 
                                        style="background-color: #f79471; color: white;" 
                                        data-bs-toggle="tooltip" title="Remove from Wishlist">
                                    <i class="bi bi-heart-fill"></i> 
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection