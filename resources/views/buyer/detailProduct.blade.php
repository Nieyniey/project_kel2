@extends('layouts.app')

@section('title', $product->name)

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
    
    body {
        background-color: #FFFBE8; 
    }

    .page-content-wrapper {
        background:#FFFBE8;
        padding:20px;
    }
    
    .primary-text-color {
        color: #6C2207 !important; 
    }
    
    .accent-color {
        color: #F3D643 !important; /* Yellow accent for chat/love */
    }
    
    .price-color {
        color: #FC5801 !important; /* Orange accent for price/main actions */
    }

    /* Wishlist Button Styling */
    .product-action-circle {
        width: auto;
        height: auto;
        padding: 0;
        border: none;
        background-color: transparent;
        border-radius: 0;
        display: inline-flex; 
        align-items: center;
        justify-content: center;
        outline: none; 
        box-shadow: none; 
        color: inherit; 
    }
    
    .product-action-circle i {
        color: #ccc !important; 
        transition: color 0.2s;
        font-size: 1.1rem;
    }

    .product-action-circle.active i {
        color: #F3D643 !important; /* Active heart color */
    }

    .product-action-circle:hover i {
        color: #F3D643 !important; /* Hover heart color */
    }
    
    .product-action-circle:hover {
        cursor: pointer;
    }
    
    .btn-chat {
        background-color: #F3D643 !important; 
        transition: background-color 0.2s;
    }
    .btn-chat:hover {
        background-color: #E6C83B !important; /* Slightly darker yellow on hover */
    }

    .btn-add-cart {
        background-color: #FC5801 !important; /* Main action color */
        transition: background-color 0.2s;
    }
    .btn-add-cart:hover {
        background-color: #e54c00 !important; /* Darker orange on hover */
    }
    
    .qty-btn {
        transition: background-color 0.2s, border-color 0.2s;
    }
    .qty-btn:hover {
        background-color: #eee;
    }
    
    .similar-product-card {
        background: transparent; 
        padding: 15px; 
        border-radius: 10px; 
        text-align: center;
    }
    
    .similar-product-link:hover .similar-product-card {
        transform: none; 
    }
    
    .similar-product-image {
        display: block; 
        margin-left: auto;
        margin-right: auto;
        
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out; 
    }

    /* Image Hover effect: Pop up and increase shadow */
    .similar-product-link:hover .similar-product-image {
        transform: translateY(-5px) scale(1.03); /* Pops up and scales slightly */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Prominent shadow on hover */
    }
</style>

<div class="header-fixed">
    <div style="max-width:1400px; margin: 0 auto; padding: 0 20px;"> 
        <div style="display:flex; align-items:center;">
            <a href="javascript:history.back()" 
               class="text-decoration-none price-color" 
               style="font-size: 1.5rem; margin-right: 15px; text-decoration: none;"
               title="Kembali">
                &leftarrow;
            </a>
            <h5 style="font-weight:bold; margin-bottom:0;" class="price-color">
                Detail Produk
            </h5>
        </div>
    </div>
</div>

<div class="page-content-wrapper">

    {{-- PRODUCT DETAIL CARD --}}
    <div style="background:white; margin-top:15px; padding:20px; border-radius:15px; display:flex; gap:20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <img src="{{ asset($product->image_path) }}"
              style="width:350px; height:350px; border-radius:10px; object-fit:cover;">

        <div style="flex:1;">
            <h2 style="font-size:20px; font-weight:bold; margin-bottom:5px;" class="primary-text-color">
                {{ $product->name }}
            </h2>

            <div style="display:flex; align-items:center; margin-bottom:10px;">
                <a href="{{ route('seller.profile', $product->seller->seller_id) }}" class="flex-shrink-0">
                    <img src="/images/profile.png"
                       style="width:35px; height:35px; border-radius:50%; margin-right:10px; object-fit:cover; border: 1px solid #ddd;">
                </a>

                <div>
                    <span style="font-weight:bold; font-size:14px;" class="primary-text-color">
                        {{ $product->seller->store_name }}
                    </span><br>
                    <span style="font-size:12px;" class="primary-text-color">
                        ⭐ 0 (0)
                    </span>
                </div>

                <a href="{{ route('chat.show', $product->seller->user_id) }}"
                    class="btn-chat"
                    style="margin-left:auto; padding:6px 12px; border:none;
                            border-radius:6px; color:white; text-decoration:none;">
                    Chat
                </a>
            </div>

            <p style="margin-top:10px;" class="primary-text-color">{{ $product->description }}</p>

            <div style="font-size:20px; font-weight:bold; margin-top:15px;" class="price-color">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <div style="display:flex; align-items:center; margin-top:15px; gap:10px;">

                <button id="qty-minus" class="qty-btn primary-text-color"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc; background:white;">-</button>

                <span id="qty-number" class="primary-text-color">1</span>

                <button id="qty-plus" class="qty-btn primary-text-color"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc; background:white;">+</button>

                <button id="add-btn" class="btn-add-cart"
                        style="padding:8px 18px; border:none; color:white; border-radius:6px;">
                    Add
                </button>

                {{-- LOVE BUTTON WITH STATUS COLOR --}}
            @php
                $user = Auth::user(); 
                if ($user) {
                    $is_in_wishlist = $user->inWishlist($product->product_id);
                } else {
                    $is_in_wishlist = false;
                }
            @endphp

            <button type="button" 
                class="product-action-circle add-to-wishlist-btn {{ $is_in_wishlist ? 'active' : '' }}" 
                data-product-id="{{ $product->product_id }}"
                data-action-url="{{ route('wishlist.add-ajax') }}" 
                title="Add to Wishlist">
                <i class="bi bi-heart-fill"></i>
            </button>

            </div>

            <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="qty" id="qty-input" value="1">
            </form>

        </div>
    </div>

    {{-- SIMILAR PRODUCTS --}}
    <h3 style="margin-top:25px; font-weight:bold;" class="price-color">Produk Mirip</h3>
    <div style="background: transparent; padding:0; border-radius:15px; margin-top:10px;">
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px;">
            @foreach($similarProducts as $similar)
                <a href="{{ route('products.show', $similar->product_id) }}"
                   class="similar-product-link"
                   style="text-decoration:none; color:black;">

                    <div class="similar-product-card"> 

                        <img src="{{ asset($similar->image_path) }}"
                             class="similar-product-image" 
                             style="width:100%; height:160px; object-fit:cover; border-radius:8px;">

                        <div style="margin-top:8px; font-weight:bold;" class="primary-text-color">
                            {{ $similar->name }}
                        </div>

                        <div style="margin-top:5px;" class="price-color">
                            Rp {{ number_format($similar->price, 0, ',', '.') }}
                        </div>

                    </div>

                </a>
            @endforeach
        </div>
    </div>

</div>

{{-- JAVASCRIPT --}}
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    let qty = 1;
    const maxStock = {{ $product->stock }};
    const qtyNumber = document.getElementById("qty-number");
    const qtyInput = document.getElementById("qty-input");
    const qtyMinus = document.getElementById("qty-minus");
    const qtyPlus = document.getElementById("qty-plus");

    // Function to update quantity display and input
    const updateQty = () => {
        qtyNumber.innerText = qty;
        qtyInput.value = qty;
    };
    
    // MINUS
    qtyMinus.onclick = () => {
        if (qty > 1) {
            qty--;
        }
        updateQty();
    };

    // PLUS – LIMIT TIDAK BOLEH > STOCK
    qtyPlus.onclick = () => {
        if (qty < maxStock) {
            qty++;
        } else {
            alert("Stock hanya tersedia " + maxStock);
        }
        updateQty();
    };

    // Submit add to cart
    document.getElementById("add-btn").onclick = () => {
        document.getElementById("add-to-cart-form").submit();
    };

});

// jQuery for Wishlist functionality
$(document).ready(function() {
        $.ajaxSetup({
            headers: {
                // Ensure the meta tag for CSRF is in your layouts/app.blade.php
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
                    if (response.status === 'success') {
                        if (response.is_active) {
                            button.addClass('active');
                            // Optional: Show a message that it was added
                        } else {
                            button.removeClass('active');
                            // Optional: Show a message that it was removed
                        }
                    } else {
                        alert('Action failed: ' + response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('Please log in to use this feature.');
                    } else {
                        alert('An unknown error occurred. Status: ' + xhr.status);
                    }
                }
            });
        }

        $('.add-to-wishlist-btn').on('click', function() {
            const button = $(this);
            const productId = button.data('product-id');
            const url = button.data('action-url');
            sendProductAction(button, url, productId);
        });
    });
</script>

@endpush
@endsection