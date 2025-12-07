@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
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
    }

    .product-action-circle.active i {
        color: #F3D643 !important; 
    }

    .product-action-circle:hover i {
        color: #F3D643 !important; 
    }
    
    .product-action-circle:hover {
        cursor: pointer;
    }
</style>
<div style="background:#f0f0f0; padding:20px;">

    <div style="background:white; padding:15px; border-radius:6px; display:flex; align-items:center;">
        <a href="{{ url()->previous() }}" style="text-decoration:none; color:#ff6f00; font-size:18px; margin-right:10px;">←</a>
        <span style="font-weight:bold; color:#ff6f00;">Detail Product</span>
    </div>

    <div style="background:white; margin-top:15px; padding:20px; border-radius:15px; display:flex; gap:20px;">

        <img src="{{ asset($product->image_path) }}"
              style="width:350px; height:350px; border-radius:10px; object-fit:cover;">

        <div style="flex:1;">
            <h2 style="font-size:20px; font-weight:bold; margin-bottom:5px;">
                {{ $product->name }}
            </h2>

            <div style="display:flex; align-items:center; margin-bottom:10px;">
                <a href="{{ route('seller.profile', $product->seller->seller_id) }}" class="flex-shrink-0">
                    <img src="/images/profile.png"
                       style="width:35px; height:35px; border-radius:50%; margin-right:10px;">
                </a>

                <div>
                    <span style="font-weight:bold; font-size:14px;">
                        {{ $product->seller->store_name }}
                    </span><br>
                    <span style="font-size:12px; color:#666;">
                        ⭐ 0 (0)
                    </span>
                </div>

                <a href="{{ route('chat.show', $product->seller->user_id) }}"
                    style="margin-left:auto; padding:6px 12px; background:#ff8f00; border:none;
                            border-radius:6px; color:white; text-decoration:none;">
                    Chat
                </a>
            </div>

            <p style="margin-top:10px; color:#444;">{{ $product->description }}</p>

            <div style="font-size:20px; font-weight:bold; margin-top:15px; color:#ff6f00;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <div style="display:flex; align-items:center; margin-top:15px; gap:10px;">

                <button id="qty-minus"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">-</button>

                <span id="qty-number">1</span>

                <button id="qty-plus"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">+</button>

                <button id="add-btn"
                        style="background:#ff6f00; padding:8px 18px; border:none; color:white; border-radius:6px;">
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
                <i class="bi bi-heart-fill" style="font-size: 1.1rem;"></i>
            </button>

            </div>

            <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="qty" id="qty-input" value="1">
            </form>

        </div>
    </div>

    <h3 style="margin-top:25px; color:#ff6f00; font-weight:bold;">Similar Products</h3>
    <div style="background:white; padding:20px; border-radius:15px; margin-top:10px;">
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
            @foreach($similarProducts as $similar)
                <a href="{{ route('products.show', $similar->product_id) }}"
                   style="text-decoration:none; color:black;">

                    <div style="background:#f5edd1; padding:15px; border-radius:10px; text-align:center;">

                        <img src="{{ asset($similar->image_path) }}"
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

{{-- JAVASCRIPT --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {

        /* ---------------- QTY LOGIC ---------------- */
        let qty = 1;
        const qtyNumber = document.getElementById("qty-number");
        const qtyInput = document.getElementById("qty-input");

        document.getElementById("qty-minus").onclick = () => {
            if (qty > 1) qty--;
            qtyNumber.innerText = qty;
            qtyInput.value = qty;
        };

        document.getElementById("qty-plus").onclick = () => {
            qty++;
            qtyNumber.innerText = qty;
            qtyInput.value = qty;
        };

        document.getElementById("add-btn").onclick = () => {
            document.getElementById("add-to-cart-form").submit();
        };

    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function sendProductAction(button, url, productId) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
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
                        } else {
                            button.removeClass('active');
                        }
                    } else {
                        alert('Action failed: ' + response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('Please log in to use this feature.');
                    } else {
                        alert('An unknown error occurred.');
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