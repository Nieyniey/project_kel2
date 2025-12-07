@extends('layouts.main')

@section('title', 'Payment')

@section('content')

<style>
    .method-card {
        transition: 0.2s;
    }
    .method-card.selected {
        border: 2px solid #FF6E00 !important;
        background: #FFF1E0;
    }
</style>

<div style="
    width: 100%;
    min-height: 100vh;
    background:#ffffff;
    padding:30px 40px;
">

    {{-- BACK --}}
    <a href="/cart" style="color:#FF6E00; font-size:18px; text-decoration:none;">
        ← Kembali
    </a>

    {{-- HEADER --}}
    <h2 style="margin-top:20px; font-weight:700;">Hai, {{ Auth::user()->name }},</h2>

    <p style="font-size:18px; margin-bottom:20px;">
        Anda perlu membayar 
        <span style="color:#FF6E00; font-weight:700;">
            Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </span>
    </p>

    {{-- ADDRESS BOX --}}
    <div style="
        background:#FFF7E6;
        padding:15px 20px;
        border-radius:10px;
        margin-bottom:30px;
        font-size:14px;
        border:1px solid #FFE2B3;
    ">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <b>Alamat:</b><br>
                {{ $address->address_text }}
                {{ $address->city ? ', '.$address->city : '' }}
                {{ $address->postal_code ? ', '.$address->postal_code : '' }}
            </div>

            <a href="{{ route('address.change.page', $order->order_id) }}"
                style="
                    padding:6px 12px;
                    background:#FF6E00;
                    color:white;
                    border-radius:6px;
                    text-decoration:none;
                    font-size:13px;
                ">
                Ubah Alamat
            </a>

        </div>
    </div>

    {{-- MAIN TWO COLUMN --}}
    <div style="display:flex; gap:45px; align-items:flex-start;">

        {{-- LEFT COLUMN --}}
        <div style="
            flex:1;
            background:white;
            border-radius:12px; 
            padding:30px;
            box-shadow:0 3px 10px rgba(0,0,0,0.08);
        ">

            @foreach ($paymentMethods as $method)
                <div style="margin-bottom:30px;">

                    <label style="font-weight:700; display:block; margin-bottom:12px;">
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
                                ">
                                <img src="{{ asset('icons/'.$icon) }}" 
                                     style="width:40px; height:28px; object-fit:contain;">
                                <span>{{ strtoupper(pathinfo($icon, PATHINFO_FILENAME)) }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach

        </div>

        {{-- RIGHT COLUMN --}}
        <div style="width:400px;">

            <div style="
                background:white;
                padding:30px;
                border-radius:12px;
                box-shadow:0 3px 10px rgba(0,0,0,0.08);
            ">

                <h4 style="font-weight:700; margin-bottom:20px;">Ringkasan</h4>

                @foreach ($order->items as $item)
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span>{{ $item->product->name }} (x{{ $item->qty }})</span>
                        <span>
                            Rp {{ number_format($item->price_per_item * $item->qty, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach

                <hr style="margin:20px 0;">

                <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                    <b>Total pesanan</b>
                    <b style="color:#FF6E00;">
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
                            background:#FF6E00;
                            color:white;
                            padding:14px;
                            font-size:17px;
                            font-weight:600;
                            border:none;
                            border-radius:10px;
                            cursor:pointer;
                        ">
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
