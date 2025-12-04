@extends('layouts.main')

@section('title', 'Track Order')

@section('content')

<div style="padding: 20px 40px; max-width:1200px; margin:auto;">

    <a href="/" style="color:#FF6E00; font-size:20px; text-decoration:none;">
        ← Track Order
    </a>

    <h1 style="margin-top:20px; font-weight:700;">Track Order</h1>

    <div style="margin-top:25px; background:#FFFBE8; padding:15px; border-radius:15px;">

        <div style="font-weight:600; font-size:18px; margin-bottom:15px;">
            🧭 Track Order
        </div>

        @foreach ($orders as $order)

        <div style="
            background:white;
            padding:15px;
            border-radius:15px;
            margin-bottom:20px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        ">

            {{-- TOP ROW: Shop + Status --}}
            <div style="
                display:flex; justify-content:space-between;
                font-size:16px; margin-bottom:10px;
            ">
                <b>{{ $order['shop'] }}</b>

                <span style="
                    color:{{ $order['status']=='Dikirim' ? '#FF8A00' : '#4CAF50' }};
                    font-weight:600;
                ">
                    {{ $order['status'] }}
                </span>
            </div>

            {{-- CONTENT ROW --}}
            <div style="display:flex; gap:15px;">

                {{-- IMAGE --}}
                <img src="{{ asset($order['image']) }}"
                     style="width:100px; height:100px; border-radius:12px; object-fit:cover;">

                {{-- INFO --}}
                <div style="flex:1;">
                    <b style="font-size:16px;">{{ $order['name'] }}</b>
                    <div style="font-size:14px; color:gray; margin-top:4px;">
                        {{ $order['desc'] }}
                    </div>

                    <div style="margin-top:10px; font-size:16px; color:#FF6E00; font-weight:600;">
                        Total: Rp {{ number_format($order['price'], 0, ',', '.') }}
                    </div>
                </div>

                {{-- BUTTON --}}
                <div style="display:flex; align-items:center;">
                    <a href="{{ route('buyer.settings', ['tab' => 'orders']) }}"
                       style="
                            padding:8px 15px;
                            background:#FF6E00;
                            color:white;
                            border-radius:8px;
                            text-decoration:none;
                            font-size:14px;
                        ">
                        Lihat Detail
                    </a>
                </div>

            </div>
        </div>

        @endforeach

    </div>

</div>

@endsection
