@extends('layouts.app')

@section('content')
<div style="background:#f0f0f0; padding:20px;">

    <!-- Header -->
    <div style="background:white; padding:15px; border-radius:6px; display:flex; align-items:center;">
        <a href="{{ url()->previous() }}" style="text-decoration:none; color:#ff6f00; font-size:18px; margin-right:10px;">←</a>
        <span style="font-weight:bold; color:#ff6f00;">Detail Product</span>
    </div>

    <!-- MAIN PRODUCT CARD -->
    <div style="background:white; margin-top:15px; padding:20px; border-radius:15px; display:flex; gap:20px;">

        <!-- LEFT IMAGE -->
        <img src="{{ asset($product->image_path) }}"
             style="width:350px; height:350px; border-radius:10px; object-fit:cover;">

        <!-- RIGHT INFO -->
        <div style="flex:1;">
            <h2 style="font-size:20px; font-weight:bold; margin-bottom:5px;">
                {{ $product->name }}
            </h2>

            <!-- SELLER -->
            <div style="display:flex; align-items:center; margin-bottom:10px;">
                <img src="/images/profile.png"
                     style="width:35px; height:35px; border-radius:50%; margin-right:10px;">

                <div>
                    <span style="font-weight:bold; font-size:14px;">
                        {{ $product->seller->store_name }}
                    </span><br>
                    <span style="font-size:12px; color:#666;">
                        ⭐ 0 (0)
                    </span>
                </div>

                <!-- CHAT BUTTON -->
                <a href="{{ route('chat.show', $product->seller->user_id) }}"
                    style="margin-left:auto; padding:6px 12px; background:#ff8f00; border:none;
                           border-radius:6px; color:white; text-decoration:none;">
                    Chat
                </a>
            </div>

            <!-- Description -->
            <p style="margin-top:10px; color:#444;">{{ $product->description }}</p>

            <!-- Price -->
            <div style="font-size:20px; font-weight:bold; margin-top:15px; color:#ff6f00;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <!-- Quantity + Add Cart -->
            <div style="display:flex; align-items:center; margin-top:15px; gap:10px;">

                <!-- Minus -->
                <button id="qty-minus"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">-</button>

                <!-- Number -->
                <span id="qty-number">1</span>

                <!-- Plus -->
                <button id="qty-plus"
                        style="padding:3px 10px; border-radius:4px; border:1px solid #ccc;">+</button>

                <!-- Add to Cart -->
                <button id="add-btn"
                        style="background:#ff6f00; padding:8px 18px; border:none; color:white; border-radius:6px;">
                    Add
                </button>

                <!-- LOVE BUTTON WITH STATUS COLOR -->
                <button id="wishlist-btn"
                    data-product-id="{{ $product->product_id }}"
                    style="
                        background:none;
                        border:none;
                        font-size:22px;
                        cursor:pointer;
                        color:{{ $isFavorite ? '#ff6f00' : '#aaa' }};
                    ">
                    ♥
                </button>

            </div>

            <!-- Hidden Add to Cart Form -->
            <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="qty" id="qty-input" value="1">
            </form>

        </div>
    </div>

    <!-- SIMILAR PRODUCTS -->
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


    /* ---------------- WISHLIST AJAX ---------------- */
    const wBtn = document.getElementById("wishlist-btn");

    wBtn.addEventListener("click", function () {

        let productId = this.dataset.productId;

        fetch("{{ route('wishlist.add-ajax') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === 'added') {
                wBtn.style.color = "#ff6f00";   // oren
            } else {
                wBtn.style.color = "#aaa";      // abu
            }

        });

    });

});
</script>

@endsection
