@extends('layouts.main')

@section('title', 'Payment')

@section('content')

<div style="max-width: 1250px; margin:30px auto; padding:20px;">

    {{-- BACK --}}
    <a href="/cart" style="color:#FF6E00; font-size:18px; text-decoration:none;">
        ← Payment
    </a>

    {{-- HEADER --}}
    <h2 style="margin-top:20px; font-weight:700;">Hi, {{ Auth::user()->name }},</h2>

    <p style="font-size:18px; margin-bottom:20px;">
        You need to pay 
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
                <b>Address:</b><br>

                {{ $address->address_text }}
                {{ $address->city ? ', '.$address->city : '' }}
                {{ $address->postal_code ? ', '.$address->postal_code : '' }}
            </div>

            {{-- BUTTON CHANGE ADDRESS --}}
            <a href="{{ route('address.change.page', $order->order_id) }}"
                style="
                    padding:6px 12px;
                    background:#FF6E00;
                    color:white;
                    border-radius:6px;
                    text-decoration:none;
                    font-size:13px;
                ">
                Change Address
            </a>

        </div>
    </div>

    {{-- MAIN 2 COLUMN WRAPPER --}}
    <div style="display:flex; gap:35px; align-items:flex-start;">

        {{-- LEFT COLUMN – PAYMENT OPTIONS --}}
        <div style="
            flex:1; background:white; border-radius:12px; 
            padding:25px; box-shadow:0 3px 10px rgba(0,0,0,0.08);
        ">

            @foreach ($paymentMethods as $method)
                <div style="margin-bottom:25px;">

                    <label style="font-weight:700; display:block; margin-bottom:10px;">
                        {{ $method['name'] }}
                    </label>

                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        @foreach ($method['icons'] as $icon)
                            <div onclick="selectMethod('{{ $method['name'] }}')" 
                                 style="
                                    padding:10px 15px;
                                    border:1px solid #DDD;
                                    border-radius:8px;
                                    display:flex;
                                    align-items:center;
                                    gap:10px;
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

        {{-- RIGHT COLUMN – SUMMARY --}}
        <div style="width:380px;">

            <div style="
                background:white;
                padding:25px;
                border-radius:12px;
                box-shadow:0 3px 10px rgba(0,0,0,0.08);
            ">

                <h4 style="font-weight:700; margin-bottom:15px;">Summary</h4>

                @foreach ($order->items as $item)
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span>{{ $item->product->name }} (x{{ $item->qty }})</span>
                        <span>
                            Rp {{ number_format($item->price_per_item * $item->qty, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach

                <hr style="margin:15px 0;">

                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <b>Total order amount</b>
                    <b style="color:#FF6E00;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </b>
                </div>

                {{-- PAY BUTTON --}}
                <form action="{{ route('payment.pay', $order->order_id) }}" method="POST" onsubmit="return validatePayment()">
                    @csrf
                    <input type="hidden" id="selected-method" name="method" required>

                    <button type="submit"
                        style="
                            width:100%;
                            margin-top:20px;
                            background:#FF6E00;
                            color:white;
                            padding:12px;
                            font-size:16px;
                            font-weight:600;
                            border:none;
                            border-radius:10px;
                            cursor:pointer;
                        ">
                        Pay
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

<script>
function selectMethod(method) {
    document.getElementById('selected-method').value = method;
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
