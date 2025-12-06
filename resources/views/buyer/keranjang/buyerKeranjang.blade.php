@extends('layouts.main')

@section('title', 'Keranjang')

@section('content')

<div style="
    width: 100%;
    padding: 30px;
    background: #FFFBE8;
    min-height: 100vh;
">

    {{-- HEADER --}}
    <div style="display:flex; gap:10px; align-items:center; margin-bottom:20px;">
        <a href="/home" style="color:#FF6E00; font-size:22px; text-decoration:none;">←</a>
        <span style="font-weight:600; font-size:20px;">Keranjang Belanja</span>
    </div>

    {{-- TABLE HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        padding:10px 15px;
        background:#FAEED0;
        border-radius:10px;
        font-weight:600;
        margin-bottom:20px;
    ">
        <span style="width:45%;">Product</span>
        <span style="width:20%;">Price</span>
        <span style="width:20%;">Quantity</span>
        <span style="width:10%;">Remove</span>
    </div>

    {{-- CART ITEMS --}}
    @foreach ($items as $item)
    <div class="cart-item"
        data-item-id="{{ $item->cart_item_id }}"
        style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px;
            background:white;
            border-radius:15px;
            margin-bottom:15px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        "
    >

        {{-- CHECKBOX --}}
        <input type="checkbox"
            class="item-check"
            data-price="{{ $item->product->price }}"
            style="width:20px; height:20px; margin-right:15px;">

        {{-- IMAGE --}}
        <div style="width:12%;">
            <img src="{{ asset($item->product->image) }}"
                style="width:100%; height:90px; object-fit:cover; border-radius:10px;">
        </div>

        {{-- INFO --}}
        <div style="width:30%;">
            <div style="font-weight:600;">{{ $item->product->name }}</div>
            <div style="color:gray; font-size:14px;">
                {{ $item->product->description }}
            </div>
        </div>

        {{-- PRICE --}}
        <div style="width:15%; font-weight:600; color:#FF6E00;">
            Rp {{ number_format($item->product->price, 0, ',', '.') }}
        </div>

        {{-- QTY --}}
        <div style="width:15%; display:flex; align-items:center; gap:10px;">
            <button class="qty-btn" data-action="minus"
                style="width:28px; height:28px; border-radius:50%; background:#FFF3D2; border:none;">
                -
            </button>

            <span class="qty-number">{{ $item->qty }}</span>

            <button class="qty-btn" data-action="plus"
                style="width:28px; height:28px; border-radius:50%; background:#FFF3D2; border:none;">
                +
            </button>
        </div>

        {{-- DELETE BUTTON (BENAR DI SINI!!) --}}
        <button class="delete-btn"
            style="background:none; border:none; cursor:pointer; font-size:22px;">
            ❌
        </button>

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

        <div style="display:flex; justify-content:space-between;">
            <span>Subtotal</span>
            <span id="subtotal">Rp 0</span>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <span>Ongkir</span>
            <span id="shipping">Rp {{ number_format($summary['shipping'],0,',','.') }}</span>
        </div>

        <hr>

        <div style="display:flex; justify-content:space-between; font-weight:700;">
            <span>Total</span>
            <span id="total" style="color:#FF6E00;">Rp 0</span>
        </div>

        <form id="place-order-form" method="POST" action="{{ route('orders.place') }}">
            @csrf
        </form>

        <a id="checkout-btn" href="javascript:void(0)"
            style="
                width:100%;
                background:#CCC;
                display:block;
                text-align:center;
                padding:10px;
                color:white;
                border-radius:10px;
                margin-top:10px;
            ">
            Checkout
        </a>
    </div>

</div>

@endsection

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

            if (checkbox && checkbox.checked) {
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
            checkoutBtn.style.pointerEvents = 'auto';
            checkoutBtn.onclick = () => document.getElementById('place-order-form').submit();
        } else {
            checkoutBtn.style.background = "#CCC";
            checkoutBtn.style.pointerEvents = 'none';
            checkoutBtn.onclick = null;
        }
    }

    // ⬅⬅⬅ FIX 1: panggil saat pertama dibuka!
    updateSummary();

    // Checkbox
    document.querySelectorAll('.item-check').forEach(check => {
        check.addEventListener('change', updateSummary);
    });

    // Qty button
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function () {

            let item = this.closest('.cart-item');
            let itemId = item.dataset.itemId;
            let number = item.querySelector('.qty-number');
            let qty = parseInt(number.innerText);

            if (this.dataset.action === "minus") {
                if (qty === 1) {
                    if (confirm("Hapus produk dari keranjang?")) {
                        removeItem(item, itemId);
                    }
                    return;
                }
                qty--;
            }

            if (this.dataset.action === "plus") {
                qty++;
            }

            number.innerText = qty;

            fetch("{{ route('cart.updateQty') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ item_id: itemId, qty })
            });

            updateSummary();
        });
    });

    // Delete btn
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            let item = this.closest('.cart-item');
            let itemId = item.dataset.itemId;

            if (!confirm("Hapus produk dari keranjang?")) return;

            removeItem(item, itemId);
        });
    });

    function removeItem(item, itemId) {
        fetch("{{ route('cart.deleteItem') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ item_id: itemId })
        }).then(() => {
            item.remove();
            updateSummary();
        });
    }
});

</script>
