@extends('layouts.main')

@section('title', 'Payment')

@section('content')

<div style="padding: 20px 40px; max-width:1250px; margin:auto;">

    {{-- BACK --}}
    <a href="/cart" style="color:#FF6E00; font-size:20px; text-decoration:none; display:flex; align-items:center; gap:6px;">
        ← Payment
    </a>

    <h1 style="margin-top:20px; font-weight:700;">Hi, John,</h1>

    <p style="font-size:18px;">
        You need to pay
        <span style="color:#FF6E00; font-weight:700;">Rp 2.002.000</span>
    </p>


    <div style="display:flex; gap:40px; align-items:flex-start; margin-top:20px;">

        {{-- LEFT SECTION --}}
        <div style="width:60%;">

            {{-- ADDRESS BOX --}}
            <div style="
                background:white;
                padding:18px;
                border-radius:12px;
                border:2px solid #FFE3C2;
                margin-bottom:25px;
            ">
                <b style="display:flex; align-items:center; gap:6px; font-size:16px;">
                    📍 Address
                </b>

                <div style="margin-top:6px; color:#333; line-height:1.4;">
                    {{ $address }}
                </div>

                <a href="/change-address" 
                   style="color:#FF6E00; margin-top:10px; display:inline-block; font-size:14px;">
                   Change Address
                </a>
            </div>


            {{-- PAYMENT METHODS --}}
            <div style="
                background:white;
                padding:20px;
                border-radius:12px;
                box-shadow:0 2px 6px rgba(0,0,0,0.05);
            ">
                @foreach ($paymentMethods as $method)
                    <label class="payment-row">

                        <div class="left">
                            <span class="radio-select" data-method="{{ $method['name'] }}"></span>
                            <span class="pay-name">{{ $method['name'] }}</span>
                        </div>

                        <div class="icons">
                            @foreach ($method['icons'] as $icon)
                                <img src="{{ asset('icons/'.$icon) }}" class="pay-icon">
                            @endforeach
                        </div>

                    </label>

                    @if (!$loop->last)
                        <hr>
                    @endif
                @endforeach
            </div>
        </div>



        {{-- RIGHT SUMMARY --}}
        <div style="width:40%;">
            <div style="
                background:white;
                padding:20px;
                border-radius:12px;
                box-shadow:0 2px 6px rgba(0,0,0,0.05);
            ">
                <h2 style="font-size:22px; font-weight:700; margin-bottom:15px;">Summary</h2>

                <div style="margin-bottom:10px;">
                    <b>Sepeda BMX Remaja</b>
                    <div style="font-size:13px; color:gray;">
                        Quantity: 1 × Rp 2.000.000
                    </div>
                </div>

                <div style="
                    display:flex;
                    justify-content:space-between;
                    padding-bottom:6px;
                    border-bottom:1px solid #eee;
                ">
                    <span>Transportation Fee</span>
                    <span>Rp 2.000</span>
                </div>

                <div style="
                    display:flex;
                    justify-content:space-between;
                    margin-top:15px;
                    font-size:18px;
                    font-weight:700;
                ">
                    <span>Total order amount</span>
                    <span style="color:#FF6E00;">Rp 2.002.000</span>
                </div>

                <a id="pay-btn" href="javascript:void(0)"
                    style="
                        display:block;
                        margin-top:20px;
                        background:#CCC;
                        color:white;
                        padding:12px;
                        text-align:center;
                        border-radius:10px;
                        font-weight:600;
                        text-decoration:none;
                        pointer-events:none;
                    ">
                    Pay
                </a>

            </div>
        </div>


    </div>
</div>

@endsection


{{-- ================= CUSTOM CSS ================= --}}
<style>
.payment-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 0;
    cursor:pointer;
}

.payment-row .left {
    display:flex;
    align-items:center;
    gap:12px;
}

.pay-name {
    font-size:16px;
    font-weight:500;
}

.radio-select {
    width:20px;
    height:20px;
    border:2px solid #FF6E00;
    border-radius:50%;
    display:inline-block;
    position:relative;
    cursor:pointer;
}

.radio-select.active::after {
    content:"";
    width:10px;
    height:10px;
    background:#FF6E00;
    border-radius:50%;
    position:absolute;
    top:50%; left:50%;
    transform:translate(-50%,-50%);
}

.icons {
    display:flex;
    gap:8px;
}

.pay-icon {
    height:22px;
}
</style>


{{-- ================= JS ================= --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    const radios = document.querySelectorAll('.radio-select');
    const payBtn = document.getElementById('pay-btn');

    radios.forEach(r => {
        r.addEventListener('click', function() {

            // Remove old selection
            radios.forEach(a => a.classList.remove('active'));

            // Set new selection
            this.classList.add('active');

            // Allow payment
            payBtn.style.background = "#FF6E00";
            payBtn.style.pointerEvents = "auto";
            payBtn.href = "/payment-success";
        });
    });

});
</script>
