@extends('layouts.app')

@section('title', 'Payment')

@section('content')

<style>
    /* Global Styles for Consistency */
    body {
        background-color: #FFFBE8; /* REVISION 1: Page background */
        color: #6C2207; /* REVISION 1: Default text color */
    }
    
    /* Fixed Header Styling (REVISION 3) */
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
    
    /* Utility colors */
    .primary-text-color {
        color: #6C2207 !important;
    }
    .accent-color {
        color: #FC5801 !important; /* Used for price and key buttons */
    }
    
    /* Payment Method Card */
    .method-card {
        transition: 0.2s;
    }
    .method-card.selected {
        border: 2px solid #FC5801 !important; /* Adjusted color to #FC5801 for consistency */
        background: #FFF1E0;
    }
    
    /* Address Box Style (REVISION 2) */
    .address-box {
        background:#E8E0BB !important; /* REVISION 2: Address box color */
        border:1px solid #D8C8B4 !important; /* Slightly darker border for #E8E0BB */
    }
    
    /* Ensure all text inside the main content is the correct color */
    .content-area * {
        color: inherit; /* Inherits the #6C2207 from body/container */
    }
    .content-area .accent-color {
        color: #FC5801 !important; /* Override for accents */
    }
</style>

{{-- REVISION 3: FIXED HEADER IMPLEMENTATION --}}
<div class="header-fixed">
    <div style="max-width:1400px; margin: 0 auto; padding: 0 40px;"> 
        <div style="display:flex; align-items:center;">
            {{-- Back link should point to the cart --}}
            <a href="/cart" 
               class="text-decoration-none accent-color" 
               style="font-size: 1.5rem; margin-right: 15px; text-decoration: none;"
               title="Kembali ke Keranjang">
                &leftarrow;
            </a>
            <h5 style="font-weight:bold; margin-bottom:0;" class="accent-color">
                Pembayaran
            </h5>
        </div>
    </div>
</div>

{{-- Main Content Container --}}
<div class="content-area" style="
    width: 100%;
    min-height: 100vh;
    padding:30px 40px;
">

    {{-- HEADER --}}
    <h2 style="margin-top:20px; font-weight:700;" class="primary-text-color">Hai {{ Auth::user()->name }},</h2>

    <p style="font-size:18px; margin-bottom:20px;">
        Anda perlu membayar 
        <span class="accent-color" style="font-weight:700;">
            Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </span>
    </p>

    {{-- ADDRESS BOX (REVISION 2) --}}
    <div class="address-box" style="
        padding:15px 20px;
        border-radius:10px;
        margin-bottom:30px;
        font-size:14px;
    ">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <b class="primary-text-color">Alamat:</b><br>
                <span class="primary-text-color">
                    {{ $address->address_text }}
                    {{ $address->city ? ', '.$address->city : '' }}
                    {{ $address->postal_code ? ', '.$address->postal_code : '' }}
                </span>
            </div>

            <a href="{{ route('address.change.page', $order->order_id) }}"
                style="
                    padding:6px 12px;
                    background:#FC5801; /* Adjusted color */
                    color:white;
                    border-radius:6px;
                    text-decoration:none;
                    font-size:13px;
                    transition: background 0.2s;
                "
                onmouseover="this.style.backgroundColor='#e54c00';"
                onmouseout="this.style.backgroundColor='#FC5801';">
                Ubah Alamat
            </a>

        </div>
    </div>

    {{-- MAIN TWO COLUMN --}}
    <div style="display:flex; gap:45px; align-items:flex-start;">

        {{-- LEFT COLUMN --}}
        <div style="
            flex:1;
            background:#FFFEF7; /* Light background for the box */
            border-radius:12px; 
            padding:30px;
            box-shadow:0 3px 10px rgba(0,0,0,0.08);
        ">

            @foreach ($paymentMethods as $method)
                <div style="margin-bottom:30px;">

                    <label style="font-weight:700; display:block; margin-bottom:12px;" class="primary-text-color">
                        {{ $method['name'] }}
                    </label>

                    <div style="display:flex; gap:15px; flex-wrap:wrap;">
                        @foreach ($method['icons'] as $icon)
                            <div class="method-card"
                                onclick="selectMethod('{{ strtoupper(pathinfo($icon, PATHINFO_FILENAME)) }}', this)"
                                style="
                                    padding:12px 15px;
                                    border:1px solid #DDD;
                                    border-radius:8px;
                                    display:flex;
                                    align-items:center;
                                    gap:12px;
                                    cursor:pointer;
                                    background:white; /* Ensure icons are on white background */
                                ">
                                <img src="{{ asset('icons/'.$icon) }}" 
                                    style="width:40px; height:28px; object-fit:contain;">
                                <span class="primary-text-color">{{ strtoupper(pathinfo($icon, PATHINFO_FILENAME)) }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach

        </div>

        {{-- RIGHT COLUMN --}}
        <div style="width:400px;">

            <div style="
                background:#FFFEF7; /* Light background for the box */
                padding:30px;
                border-radius:12px;
                box-shadow:0 3px 10px rgba(0,0,0,0.08);
            ">

                <h4 style="font-weight:700; margin-bottom:20px;" class="primary-text-color">Ringkasan</h4>

                @foreach ($order->items as $item)
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span class="primary-text-color">{{ $item->product->name }} (x{{ $item->qty }})</span>
                        <span class="primary-text-color">
                            Rp {{ number_format($item->price_per_item * $item->qty, 0, ',', '.') }}
                        </span>
                    </div>
                    {{-- Tambahkan subtotal produk --}}
                    {{-- <hr>
                    <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->total_price - 10000, 0, ',', '.') }}</span>
                    </div> --}}

                    <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                        <span>Ongkir</span>
                        <span>Rp 10.000</span>
                    </div>

                    <hr>
                @endforeach

                <hr style="margin:20px 0; border-color: #d8c8b4;">

                <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                    <b class="primary-text-color">Total pesanan</b>
                    <b class="accent-color">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </b>
                </div>

                {{-- PAY BUTTON --}}
                <form action="{{ route('payment.pay', $order->order_id) }}" method="POST" onsubmit="return validatePayment()">
                    @csrf
                    <input type="hidden" id="selected-method" name="method">

                    <button type="submit"
                        style="
                            width:100%;
                            margin-top:25px;
                            background:#FC5801; /* Adjusted color */
                            color:white;
                            padding:14px;
                            font-size:17px;
                            font-weight:600;
                            border:none;
                            border-radius:10px;
                            cursor:pointer;
                            transition: background 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='#e54c00';"
                        onmouseout="this.style.backgroundColor='#FC5801';">
                        Bayar
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

<script>
function selectMethod(method, element) {
    document.getElementById('selected-method').value = method;

    document.querySelectorAll('.method-card').forEach(card => {
        card.classList.remove('selected');
    });

    element.classList.add('selected');
}

function validatePayment() {
    let method = document.getElementById('selected-method').value;
    if (!method) {
        alert("Please select a payment method first.");
        return false;
    }
    return true;
}
</script>

@endsection