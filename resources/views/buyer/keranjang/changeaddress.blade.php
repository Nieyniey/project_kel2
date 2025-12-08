@extends('layouts.main')

@section('title', 'Change Address')

@section('content')

<style>
    /* Global Styles for Consistency */
    body {
        background-color: #FFFBE8;
        color: #6C2207; /* REVISION 1: Default text color */
    }

    /* Fixed Header Styling (REVISION 2) */
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
    
    /* Container for content, ensuring consistent width/padding */
    .address-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px 20px; /* Adjusted top padding since header is fixed */
    }

    .primary-text-color {
        color: #6C2207 !important;
    }
    .accent-color {
        color: #FC5801 !important; /* Used for the back arrow/accents */
    }
</style>

{{-- REVISION 2: FIXED HEADER IMPLEMENTATION --}}
<div class="header-fixed">
    {{-- We need to change the back button URL and the title text. --}}
    <div style="max-width:900px; margin: 0 auto; padding: 0 20px;"> 
        <div style="display:flex; align-items:center;">
            {{-- Back link should point to the payment page --}}
            <a href="{{ route('payment.page', $order_id) }}" 
               class="text-decoration-none primary-text-color" 
               style="font-size: 1.5rem; margin-right: 15px;"
               title="Kembali ke Pembayaran">
                &leftarrow;
            </a>
            <h5 style="font-weight:bold; margin-bottom:0;" class="accent-color">
                Ubah Alamat
            </h5>
        </div>
    </div>
</div>

<div class="address-container">
    <p class="primary-text-color">Perbarui alamat pengiriman Anda di bawah ini:</p>
    <form method="POST" action="{{ route('address.change.save') }}"
          style="background:#FFFEF7; padding:25px; border-radius:15px; margin-top:20px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">

        @csrf

        <input type="hidden" name="order_id" value="{{ $order_id }}">

        <label style="font-weight:600;" class="primary-text-color">Alamat Lengkap</label>
        <textarea name="address" required
            style="width:100%; padding:12px; border-radius:8px; 
            border:2px solid #FF8A3D; margin-bottom:18px; height:80px; 
            background:white; color:#6C2207;" 
            placeholder="Nama jalan, gedung, dll."></textarea>
            
        <button type="submit"
            style="width:100%; background:#FC5801; border:none; padding:14px; 
            color:white; border-radius:10px; font-size:18px; margin-top:10px; 
            cursor: pointer; transition: background 0.2s;"
            onmouseover="this.style.backgroundColor='#e54c00';"
            onmouseout="this.style.backgroundColor='#FC5801';">
            Simpan Alamat
        </button>

    </form>

</div>

@endsection