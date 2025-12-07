@extends('layouts.app') 

@section('title', 'Wishlist')

@section('content')

<style>
    .header-fixed {
        background-color: #FFFEF7; /* Navigation and Right Square Background */
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

    /* Image Container */
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

    /* Action Circle/Button Styling */
    .product-action-circle {
        background-color: #6C2207; /* Dark Circle Background */
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
    
    /* Icon default color */
    .product-action-circle i {
        color: #FFFBE8; /* White/Cream Icon Default */
        font-size: 1.1rem;
    }

    /* Active State: Heart will be yellow */
    .product-action-circle.active {
        background-color: #6C2207; /* Keep the background dark */
    }

    /* Active Icon Color (The key fix for yellow icon) */
    .product-action-circle.active i {
        color: #F3D643 !important; /* Yellow/Gold Icon Color */
    }

    .product-action-circle:hover {
        background-color: #5c4a3e; 
    }
</style>

{{-- Header: Back Button and Title (Fixed/Sticky) --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('homeIn') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #FC5801!important;">
                    Wishlist
                </h5>
            </div>
        </div>
    </div>

<div class="container wishlist-content-area py-5" style="padding-top: 0px !important;">
    
    <h3 class="fw-bold mb-4" style="color: #6C2207;">Favoritku</h3>

    <div class="row g-4">
        @forelse ($wishlistItems as $item)
            @php
                $product = $item->product;
            @endphp
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
                                {{-- Fallback: Display an icon if no image path exists --}}
                                <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            @endif
                        </a>
                    </div>

                    <div class="p-2 text-center">
                         <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                            <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                            <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                        
                        <div class="d-flex justify-content-center gap-3 mt-2">
                             @php
                                 $user = Auth::user(); 
                                 $is_in_cart = $user->inCart($product->product_id); 
                             @endphp

                             {{-- Add to Cart (Bag) --}}
                             <button type="button" 
                                 class="product-action-circle add-to-cart-btn {{ $is_in_cart ? 'active' : '' }}" 
                                 data-product-id="{{ $product->product_id }}"
                                 data-action-url="{{ route('cart.add-ajax') }}"
                                 title="Add to Cart">
                                 <i class="bi bi-bag-fill"></i> 
                             </button>

                             {{-- Add to Wishlist (Heart) - ALWAYS ACTIVE HERE --}}
                             <button type="button" 
                                 class="product-action-circle add-to-wishlist-btn active" 
                                 data-product-id="{{ $product->product_id }}"
                                 data-action-url="{{ route('wishlist.add-ajax') }}" 
                                 title="Remove from Wishlist">
                                 <i class="bi bi-heart-fill"></i>
                             </button>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info" style="color: #6C2207; background-color: #FFFBE8; border-color: #FFFBE8;">
                    Belum ada barang di Wishlist.
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection

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
                const isActive = response.is_active;

                if (response.action === 'added') {
                    button.addClass('active');
                    toastr.success('Item added to ' + (url.includes('wishlist') ? 'Wishlist' : 'Cart'));
                } else {
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
                // Use a standard error handler
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

    // Listener for Cart button (Bag)
    $(document).on('click', '.add-to-cart-btn', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('action-url');
        sendProductAction(button, url, productId);
    });
});
</script>
@endpush