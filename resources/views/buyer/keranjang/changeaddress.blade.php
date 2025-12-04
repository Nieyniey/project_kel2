@extends('layouts.main')

@section('title', 'Change Address')

@section('content')

<div style="max-width:900px; margin:30px auto; padding:20px;">

    <a href="{{ route('payment.page', $order_id) }}"
       style="color:#FF6E00; font-size:20px; text-decoration:none;">
        ← Back
    </a>

    <h1 style="margin-top:20px; font-weight:700;">Change Address</h1>
    <p style="color:#555;">Update your shipping address below:</p>

    <form method="POST" action="{{ route('address.change.save') }}"
          style="background:#FFFBE8; padding:25px; border-radius:15px; margin-top:20px;">

        @csrf

        <input type="hidden" name="order_id" value="{{ $order_id }}">

        <label style="font-weight:600;">Full Address</label>
        <textarea name="address" required
            style="width:100%; padding:12px; border-radius:8px; 
            border:2px solid #FF8A3D; margin-bottom:18px; height:80px;"
            placeholder="Street name, building, etc."></textarea>

        <button type="submit"
                style="width:100%; background:#FF6E00; border:none; padding:14px; 
                color:white; border-radius:10px; font-size:18px; margin-top:10px;">
            Save Address
        </button>

    </form>

</div>

@endsection
