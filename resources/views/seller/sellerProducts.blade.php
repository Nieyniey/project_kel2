@extends('layouts.app')

@section('title', 'WTS Seller')

@section('content')
<style>
    body {
        background-color: #FFFBE8;
    }

    .wts-header {
        background-color: #FFFBE8; 
        border-bottom: 1px solid #FFFBE8; 
    } 

    .product-card {
        background-color: #FFFBE8; 
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .product-image-container {
        background-color: #D9D9D9; 
        height: 180px; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px; 
    }

    .product-image-shadow {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .product-card-link:hover .product-image-shadow {
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); 
    }

    .product-card-link {
        text-decoration: none; 
        color: inherit;
        display: block;
    }
</style>

{{-- Header --}}
<div class="container-fluid p-0" style="background-color: #FFFBE8;">
    <div class="wts-header p-3 sticky-top">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('seller.settings') }}" style="color: #6C2207;">
                <i class="bi bi-gear-fill" style="font-size: 1.5rem;"></i>
            </a>
            <a href="{{ route('seller.products') }}" class="wts-logo mx-auto">
                <img src="{{ asset('img/Logo.jpg') }}" alt="WTS Logo" style="height: 50px; width: auto; object-fit: contain;">
            </a>

            {{-- Icons Container (Right-aligned Group for Action Icons) --}}
            <div class="d-flex gap-3 align-items-center position-absolute end-0 me-3"> 
                
                {{-- Search Icon Button --}}
                <button type="button" class="btn p-0" id="search-icon-btn">
                    <i class="bi bi-search" style="font-size: 1.5rem; color: #6C2207;"></i>
                </button>
                
                {{-- Chat -> Buyer Chat Index --}}
                <a href="{{ route('chat.index') }}" style="color: #6C2207;">
                    <i class="bi bi-chat-fill" style="font-size: 1.5rem;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- SEARCH BAR CONTAINER --}}
    <div id="toggled-search-bar" class="container p-3 {{ request()->has('q') ? '' : 'd-none' }}" style="padding-top: 15px !important;">
        <div class="d-flex align-items-center w-100">
            {{-- Close button ('x') --}}
            <button type="button" class="btn-close fs-4 me-3" id="close-search-bar" aria-label="Close" style="color: #5c4a3e;"></button>

            {{-- Search Form --}}
            <form action="{{ route('seller.products.search') }}" method="GET" class="w-100 d-flex">
                <input type="search" 
                    id="search-input" 
                    name="q" 
                    class="form-control rounded-pill p-2" 
                    placeholder="Search product..." 
                    value="{{ request('q') }}"
                    style="background-color: #FFFEF7; border: 1px solid #d8c8b4;">
            </form>
        </div>
    </div>

    <div class="container py-4" style="color: #6C2207;">
        
        {{-- ADD PRODUCT BUTTON --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Produk Toko</h4>
            <a href="{{ route('seller.products.create') }}" 
               class="btn fw-bold" 
               style="background-color: #FC5801; color: white;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk
            </a>
        </div>

        <div class="row g-4 mb-5">
            @foreach($products as $product)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('seller.products.edit', $product) }}" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-container product-image-shadow">
                            @if (isset($product->image_path))
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid" 
                                     style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="p-2 text-center">
                            <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                            <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const searchBar = $('#toggled-search-bar');
        const searchInput = $('#search-input');
        const searchIconBtn = $('#search-icon-btn'); 
        
        function showSearchBar() {
            searchBar.removeClass('d-none');
        }

        function hideSearchBar() {
            searchBar.addClass('d-none');
        }

        searchIconBtn.on('click', function(e) {
            e.preventDefault(); 
            if (searchBar.hasClass('d-none')) {
                showSearchBar();
            }
        });

        $('#close-search-bar').on('click', function() {
            hideSearchBar();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endpush

@endsection