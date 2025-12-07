@extends('layouts.app') 

@section('title', $seller->store_name . ' Store')

@section('content')

<style>
    .header-fixed {
        background-color: #FFFEF7; 
        width: 100%;
        position: sticky; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    .wishlist-content-area {
        margin-top: 20px; 
    }

    body {
            background-color: #FFFBE8; 
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
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .product-image-shadow:hover {
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); 
    }

    .product-action-circle {
        background-color: #6C2207; 
        width: 32px;
        height: 32px;
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: none;
        transition: background-color 0.2s;
    }
    
    .product-action-circle i {
        color: #FFFBE8; 
        font-size: 1.1rem;
    }

    .product-action-circle.active {
        background-color: #6C2207;
    }

    .product-action-circle.active i {
        color: #F3D643 !important; 
    }

    .product-action-circle:hover {
        background-color: #5c4a3e; 
    }

    .seller-info-box {
        background-color: #FFFEF7; 
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #d8c8b4;
    }

    .seller-profile-pic {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid rgba(252, 88, 1, 0.4);
    }

    .product-list-title {
        color: #6C2207;
        margin-bottom: 25px;
        font-size: 1.5rem;
    }
</style>

{{-- Header --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('seller.products') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #FC5801!important;">
                    Profil Penjual
                </h5>
            </div>
        </div>
    </div>

<div class="container py-4">
    
    {{-- 1. SELLER INFORMATION SECTION --}}
    <div class="seller-info-box mb-5">
        <div class="d-flex align-items-start">
            {{-- Seller Profile Picture --}}
            <div class="me-4 flex-shrink-0">
                @php
                    $profileImage = $seller->user->profile_photo 
                        ? asset('storage/' . $seller->user->profile_photo) 
                        : asset('placeholder.jpg'); 
                @endphp
                <img src="{{ $profileImage }}" 
                     class="seller-profile-pic">
            </div>
            
            {{-- Seller Name and Description --}}
            <div>
                <h4 class="fw-bold mb-1" style="color: #6C2207;">{{ $seller->store_name }} Store</h4>
                <p class="text-muted mb-0" style="color: #6C2207 !important;">
                    {{ $seller->description ?? 'No description provided.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- 2. SELLER PRODUCTS LISTING --}}
    <h3 class="fw-bold product-list-title">Produk {{ $seller->name }}</h3>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-6 col-md-3 col-xl-3"> 
                <div class="product-card">
                    <div class="product-image-container product-image-shadow">
                        <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none d-block w-100 h-100 d-flex align-items-center justify-content-center">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="img-fluid" 
                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            @endif
                        </a>
                    </div>

                    <div class="p-2 text-center">
                        <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                            <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                            <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                        
                        {{-- Action buttons (Wishlist/Cart) are shown for buyer interaction --}}
                        <div class="d-flex justify-content-center gap-3 mt-2">
                            @php
                                $buyer = Auth::user(); 
                                $is_in_cart = $buyer ? $buyer->inCart($product->product_id) : false; 
                                $is_in_wishlist = $buyer ? $buyer->inWishlist($product->product_id) : false;
                            @endphp

                            {{-- Add to Cart (Bag) --}}
                            <button type="button" 
                                class="product-action-circle add-to-cart-btn {{ $is_in_cart ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('cart.add-ajax') }}"
                                title="Add to Cart">
                                <i class="bi bi-bag-fill"></i> 
                            </button>

                            {{-- Add to Wishlist (Heart) --}}
                            <button type="button" 
                                class="product-action-circle add-to-wishlist-btn {{ $is_in_wishlist ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('wishlist.add-ajax') }}" 
                                title="Add to Wishlist">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info" style="color: #6C2207; background-color: #FFFBE8; border-color: #FFFBE8;">
                    Penjual belum ada produk.
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function sendProductAction(button, url, productId) {
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                // Determine if the item is now active/inactive
                const isActive = response.is_active;

                if (response.action === 'added') {
                    // Action: Added
                    button.addClass('active');
                    toastr.success('Item added to ' + (url.includes('wishlist') ? 'Wishlist' : 'Cart'));
                } else {
                    // Action: Removed
                    button.removeClass('active');
                    toastr.warning('Item removed from ' + (url.includes('wishlist') ? 'Wishlist' : 'Cart'));
                }

                if (url.includes('wishlist') && response.action === 'removed') {
                    button.closest('.col-6').fadeOut(300, function() {
                        $(this).remove();
                        if ($('.product-card').length === 0) {
                            window.location.reload(); 
                        }
                    });
                }
            },
            error: function(xhr) {
                let message = 'An unknown error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = 'Action failed: ' + xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    }

    $(document).on('click', '.add-to-wishlist-btn', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('action-url');
        sendProductAction(button, url, productId);
    });

    $(document).on('click', '.add-to-cart-btn', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('action-url');
        sendProductAction(button, url, productId);
    });
});
</script>
@endpush

@endsection