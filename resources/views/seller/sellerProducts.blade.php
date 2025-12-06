@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Products</h2>

        <a href="{{ route('seller.products.create') }}" 
           class="btn btn-warning fw-semibold">
            Add New Product
        </a>
    </div>

    <!-- ACTIVE PRODUCTS SECTION -->
    <h4 class="fw-bold mb-3">Active</h4>

    <div class="row g-4 mb-5">
        @foreach($activeProducts as $product)
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <img src="{{ $product['image'] }}" class="card-img-top" alt="product">

                <div class="card-body text-center">
                    <h6 class="fw-bold">{{ $product['name'] }}</h6>
                    <p class="mb-2 text-muted">Rp {{ number_format($product['price'], 0, ',', '.') }},00</p>

                    <a href="{{ route('seller.products.edit', $product['id']) }}"
                       class="btn btn-sm btn-warning px-3">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- SOLD PRODUCTS SECTION -->
    <h4 class="fw-bold mb-3">Sold</h4>

    <div class="row g-4">
        @foreach($soldProducts as $product)
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <img src="{{ $product['image'] }}" class="card-img-top" alt="product">

                <div class="card-body text-center">
                    <h6 class="fw-bold">{{ $product['name'] }}</h6>
                    <p class="mb-2 text-muted">Rp {{ number_format($product['price'], 0, ',', '.') }},00</p>

                    <button class="btn btn-sm btn-secondary" disabled>Sold</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection
