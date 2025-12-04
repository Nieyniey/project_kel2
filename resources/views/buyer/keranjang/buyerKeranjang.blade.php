@extends('layouts.main')

@section('title', 'Keranjang')

@section('content')

<div style="
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #F5EEDC;
    border-radius: 20px;
">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="/" style="color:#FF6E00; font-size:22px; text-decoration:none;">←</a>
            <span style="font-weight:600; font-size:20px;">Keranjang Belanja</span>
        </div>

        <div>
            <a href="#" style="margin-right:10px; color:#FF6E00;"><i class="bi bi-search"></i></a>
            <a href="#" style="margin-right:10px; color:#FF6E00;"><i class="bi bi-heart"></i></a>
            <a href="#" style="color:#FF6E00;"><i class="bi bi-cart"></i></a>
        </div>
    </div>

    {{-- TABLE HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        font-weight:600;
        padding:10px 15px;
        background:#FFFBE8;
        border-radius:10px;
        margin-bottom:20px;
    ">
        <span style="width:50%;">Product</span>
        <span style="width:25%;">Price</span>
        <span style="width:25%;">Quantity</span>
    </div>

    {{-- CART ITEMS --}}
    @foreach ($items as $item)
    <div class="cart-item"
        style="
        display:flex;
        justify-content:space-between;
        padding:15px;
        background:white;
        border-radius:15px;
        margin-bottom:15px;
        box-shadow:0 2px 6px rgba(0,0,0,0.1);
    ">

        {{-- CHECKBOX --}}
        <div style="display:flex; align-items:center; padding-right:10px;">
            <input type="checkbox" 
                   class="item-check" 
                   data-price="{{ $item->product->price }}"
                   style="width:20px; height:20px;">
        </div>

        {{-- IMAGE --}}
        <div style="width:15%;">
            <img src="{{ asset($item->product->image) }}"
                style="width:100%; height:90px; object-fit:cover; border-radius:10px;">
        </div>

        {{-- PRODUCT INFO --}}
        <div style="width:30%;">
            <div style="font-weight:600; font-size:14px; margin-bottom:5px;">
                {{ $item->product->name }}
            </div>
            <div style="color:gray; font-size:13px;">
                {{ $item->product->description }}
            </div>
        </div>

        {{-- PRICE --}}
        <div style="width:20%; font-weight:600; color:#FF6E00; display:flex; align-items:center;">
            Rp {{ number_format($item->product->price, 0, ',', '.') }}
        </div>

        {{-- QUANTITY --}}
        <div style="width:20%; display:flex; align-items:center; gap:10px;">

            <button class="qty-btn" data-action="minus"
                style="width:28px; height:28px; border-radius:50%;
                       background:#FFF3D2; border:none; color:#FF6E00; font-size:18px;">
                -
            </button>

            <span class="qty-number">{{ $item->qty }}</span>

            <button class="qty-btn" data-action="plus"
                style="width:28px; height:28px; border-radius:50%;
                       background:#FFF3D2; border:none; color:#FF6E00; font-size:18px;">
                +
            </button>

            <a href="#" class="delete-item" style="color:red; margin-left:10px;">
                <i class="bi bi-trash"></i>
            </a>
        </div>

    </div>
    @endforeach

    {{-- ORDER SUMMARY --}}
    <div style="
        width: 350px;
        background:white;
        margin:30px auto 0;
        border-radius:15px;
        padding:20px;
        box-shadow:0 2px 8px rgba(0,0,0,0.1);
    ">
        <h4 style="font-weight:700; margin-bottom:15px;">Ringkasan Pesanan</h4>

        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
            <span>Subtotal</span>
            <span id="subtotal">Rp 0</span>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
            <span>Ongkir</span>
            <span id="shipping">Rp {{ number_format($summary['shipping'],0,',','.') }}</span>
        </div>

        <hr>

        <div style="display:flex; justify-content:space-between; font-weight:700; margin-bottom:15px;">
            <span>Total</span>
            <span id="total" style="color:#FF6E00;">Rp 0</span>
        </div>

        <a id="checkout-btn" href="javascript:void(0)"
            style="width:100%; background:#CCC; display:block; text-align:center;
                   padding:10px; color:white; border-radius:10px; font-size:16px;">
            Checkout
        </a>

    </div>

</div>

@endsection


{{-- =====================  JS QUANTITY + CHECKBOX  ===================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const shipping = {{ $summary['shipping'] }};
    const subtotalText = document.getElementById('subtotal');
    const totalText = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');

    function updateSummary() {
        let subtotal = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const checkbox = item.querySelector('.item-check');
            if (checkbox.checked) {
                let qty = parseInt(item.querySelector('.qty-number').innerText);
                let price = parseInt(checkbox.dataset.price);
                subtotal += qty * price;
            }
        });

        subtotalText.innerText = "Rp " + subtotal.toLocaleString('id-ID');

        let total = subtotal > 0 ? subtotal + shipping : 0;
        totalText.innerText = "Rp " + total.toLocaleString('id-ID');

        if (subtotal > 0) {
            checkoutBtn.style.background = "#FF6E00";
            checkoutBtn.style.pointerEvents = "auto";
            checkoutBtn.href = "/payment";
        } else {
            checkoutBtn.style.background = "#CCC";
            checkoutBtn.style.pointerEvents = "none";
            checkoutBtn.href = "javascript:void(0)";
        }
    }

    document.querySelectorAll('.item-check').forEach(check => {
        check.addEventListener('change', updateSummary);
    });

    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function () {
            let container = this.parentElement;
            let number = container.querySelector('.qty-number');
            let row = this.closest('.cart-item');
            let value = parseInt(number.innerText);

            if (this.dataset.action === "minus") {
                if (value > 1) {
                    number.innerText = value - 1;
                } else {
                    row.remove();
                }
            }

            if (this.dataset.action === "plus") {
                number.innerText = value + 1;
            }

            updateSummary();
        });
    });

    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.cart-item').remove();
            updateSummary();
        });
    });

});
</script>
