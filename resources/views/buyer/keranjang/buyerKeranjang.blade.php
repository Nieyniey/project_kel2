@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')

{{-- Injecting Styles for Fixed Header --}}
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
    .cart-page-content {
        background: #FFFBE8;
        min-height: 100vh;
    }
    .text-main-color {
        color: #6C2207 !important; /* Main text color */
    }
    .price-color {
        color: #FC5801 !important; /* Price highlight color */
    }
    .cart-item {
        color: #6C2207;
    }
    

    #checkout-btn {
        text-decoration: none !important;
    }


    .container {
        padding-left: 30px;
        padding-right: 30px;
    }
</style>

<div class="header-fixed">
    <div class="container" style="padding: 0 30px;"> 
        <div style="display:flex; align-items:center;">
            <a href="{{ route('homeIn') }}" style="font-size: 1.5rem; color:#FC5801!important; text-decoration:none; margin-right:15px;">
                &leftarrow;
            </a>
            <h5 style="font-weight:bold; margin-bottom:0; color: #FC5801!important;">
                Keranjang Belanja
            </h5>
        </div>
    </div>
</div>

<div class="cart-page-content container" style="padding-top: 30px;">

<div style="
        display:flex;
        justify-content:space-between;
        align-items: center; 
        padding:10px 15px;
        background:#E8E0BB; 
        border-radius:10px;
        font-weight:600;
        margin-bottom:20px;
    " class="text-main-color">
        
        {{-- Produk (Combined with Checkbox space) --}}
        <span style="width:47%; margin-left:85px;">Produk</span> 
        
        {{-- Harga --}}
        <span style="width:13%; text-align: left;">Harga</span> 
        
        {{-- Banyak (Centered for Qty buttons) --}}
        <span style="width:15%; text-align: center;">Banyak</span> 
        
        {{-- Hapus (Aligned with the delete button) --}}
        <span style="width:10%; text-align: right; padding-right: 10px;">Hapus</span> 

    </div>

    {{-- CART ITEMS --}}
    @foreach ($items as $item)
    <div class="cart-item text-main-color"
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

        {{-- CLICKABLE PRODUCT AREA --}}
        <a href="{{ route('products.show', $item->product->product_id) }}"
            style="text-decoration:none; color:inherit; display:flex; width:42%; gap:15px;">

            {{-- IMAGE --}}
            <div style="width:40%;">
                <img src="{{ asset($item->product->image) }}"
                    style="width:100%; height:90px; object-fit:cover; border-radius:10px;">
            </div>

            {{-- INFO --}}
            <div style="width:60%;">
                <div style="font-weight:600;">{{ $item->product->name }}</div>
                <div style="color:gray; font-size:14px; color:#6C2207;"> 
                    {{ $item->product->description }}
                </div>
            </div>

        </a>

        {{-- PRICE --}}
        <div style="width:15%; font-weight:600;" class="price-color">
            Rp {{ number_format($item->product->price, 0, ',', '.') }}
        </div>

        {{-- QTY --}}
        <div style="width:15%; display:flex; align-items:center; gap:10px;">
            <button class="qty-btn" data-action="minus"
                style="width:28px; height:28px; border-radius:50%; background:#FFF3D2; border:none; color:#6C2207;">
                -
            </button>

            <span class="qty-number">{{ $item->qty }}</span>

            <button class="qty-btn" data-action="plus"
                style="width:28px; height:28px; border-radius:50%; background:#FFF3D2; border:none; color:#6C2207;">
                +
            </button>
        </div>

        {{-- DELETE BUTTON --}}
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
    " class="text-main-color">
        <h4 style="font-weight:700; margin-bottom:15px; color:#6C2207;">Ringkasan Pesanan</h4>

        <div style="display:flex; justify-content:space-between;">
            <span>Subtotal</span>
            <span id="subtotal" class="text-main-color">Rp 0</span>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <span>Ongkir</span>
            <span id="shipping" class="text-main-color">Rp {{ number_format($summary['shipping'],0,',','.') }}</span>
        </div>

        <hr style="border-color: #d8c8b4;">

        <div style="display:flex; justify-content:space-between; font-weight:700;">
            <span>Total</span>
            <span id="total" class="price-color">Rp 0</span>
        </div>

        <form id="place-order-form" method="POST" action="{{ route('orders.place') }}">
            @csrf
            <input type="hidden" name="selected_items" id="selected-items-input">
        </form>

        {{-- REVISION 3: NO UNDERLINE --}}
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
                text-decoration: none; /* Applied revision */
            ">
            Checkout
        </a>
    </div>

</div>

@endsection


{{-- JAVASCRIPT MUST BE IN @push('scripts') if using layouts.main --}}
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const shipping = {{ $summary['shipping'] }};
    const subtotalText = document.getElementById('subtotal');
    const totalText = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');

    /* ===========================
        UPDATE SUMMARY
    ============================ */
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
        totalText.innerText = "Rp " + (subtotal > 0 ? subtotal + shipping : 0).toLocaleString('id-ID');

        // Note: The original code used #FF6E00 for the button color. I will use the established price-color #FC5801 for consistency.
        const activeColor = "#FC5801"; // Updated active color for checkout button

        checkoutBtn.style.background = subtotal > 0 ? activeColor : "#CCC";
        checkoutBtn.style.pointerEvents = subtotal > 0 ? "auto" : "none";
    }

    updateSummary();


    /* ===========================
        CHECKBOX EVENT
    ============================ */
    document.querySelectorAll('.item-check').forEach(check => {
        check.addEventListener('change', updateSummary);
    });


    /* ===========================
        QTY BUTTONS
    ============================ */
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function () {

            let item = this.closest('.cart-item');
            let itemId = item.dataset.itemId;
            let number = item.querySelector('.qty-number');
            let oldQty = parseInt(number.innerText);
            let qty = oldQty;

            if (this.dataset.action === "minus") {
                if (qty === 1) {
                    if (confirm("Hapus produk dari keranjang?")) {
                        removeItem(item, itemId);
                    }
                    return;
                }
                qty--;
            } else {
                qty++;
            }

            // SEND TO BACKEND
            let form = new FormData();
            form.append("item_id", itemId);
            form.append("qty", qty);
            
            
            fetch("{{ route('cart.updateQty') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: form
            })
            .then(async (res) => {
                let data = await res.json();

                if (!res.ok) {
                    alert(data.message);
                    return;
                }

                number.innerText = qty;
                updateSummary();
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
                alert('Gagal update kuantitas. Coba lagi.');
            });

        });
    });


    /* ===========================
        DELETE ITEM
    ============================ */
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let item = this.closest('.cart-item');
            let itemId = item.dataset.itemId;

            if (!confirm("Hapus produk dari keranjang?")) return;

            removeItem(item, itemId);
        });
    });

    function removeItem(item, itemId) {

        let fd = new FormData();
        fd.append('item_id', itemId);

        fetch("{{ route('cart.deleteItem') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: fd
        })
        .then(() => {
            item.remove();
            updateSummary();
        })
        .catch(error => {
            console.error('Error deleting item:', error);
            alert('Gagal menghapus item. Coba lagi.');
        });
    }


    /* ===========================
        CHECKOUT BUTTON
    ============================ */
    checkoutBtn.addEventListener("click", function () {

        let selected = [];

        document.querySelectorAll('.cart-item').forEach(item => {
            let checkbox = item.querySelector('.item-check');

            if (checkbox && checkbox.checked) {
                selected.push({
                    id: item.dataset.itemId,
                    qty: parseInt(item.querySelector('.qty-number').innerText)
                });
            }
        });

        if (selected.length === 0) {
            alert("Pilih minimal 1 item!");
            return;
        }
        
        // Pass the array of IDs to the form
        document.getElementById('selected-items-input').value = JSON.stringify(selected);
        document.getElementById('place-order-form').submit();
    });

});
</script>
@endpush