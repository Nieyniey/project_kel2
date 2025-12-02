<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body style="margin:0; background:#252525; font-family:Arial, sans-serif;">

{{-- MAIN WRAPPER --}}
<div style="width:100%; background:#f7f3ea; max-width:1600px; margin:0 auto;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; padding:18px 24px;">
        <img src="/images/Logo.jpg" style="height:45px;">
        <div style="font-size:22px; display:flex; align-items:center; gap:20px;">
            🔍   ❤️   🛒   👤
        </div>
    </div>

    {{-- CATEGORY ICONS --}}
    <div style="display:flex; justify-content:space-around; padding:20px 10px 10px;">
        @foreach(["Clothes","Bags","Camera","Shoes","Accessories","Electronics"] as $cat)
            <div style="text-align:center;">
                <div style="background:white; padding:15px; border-radius:15px;">
                    <img src="/images/icon.png" style="width:50px;">
                </div>
                <p style="margin-top:5px; font-size:14px;">{{ $cat }}</p>
            </div>
        @endforeach
    </div>

    {{-- BLACK FRIDAY SALE --}}
    <h2 style="text-align:center; font-size:26px; margin-top:10px;">Black Friday Sale!</h2>
    <div style="display:flex; justify-content:center; gap:20px; padding:20px;">
        @foreach([1,2,3,4] as $item)
        <div style="background:white; padding:10px; width:140px; border-radius:10px; text-align:center;">
            <img src="/images/product.png" style="width:100%; border-radius:10px;">
            <p style="margin:5px 0 0;">Item {{ $item }}</p>
            <strong style="color:#d9534f;">Rp. 99.000</strong>
        </div>
        @endforeach
    </div>

    {{-- BANNERS --}}
    <div style="display:flex; overflow-x:auto; gap:15px; padding:20px;">
        @foreach([1,2,3] as $banner)
        <img src="/images/banner{{$banner}}.png" style="height:150px; border-radius:12px;">
        @endforeach
    </div>

    {{-- SELLER OF THE WEEK --}}
    <h2 style="text-align:center; margin:40px 0 10px;">Seller Of The Week</h2>

    <div style="display:flex; justify-content:center; gap:30px; padding-bottom:30px;">
        @foreach([
            "Vintage Thrift",
            "The Goody Shop",
            "All The Small Things"
        ] as $seller)
        <div style="
            width:150px; height:220px; background:white; border-radius:14px;
            box-shadow:0 4px 6px rgba(0,0,0,0.2); text-align:center;
            padding:15px; position:relative;
        ">
            <img src="/images/card.png" style="width:100%; border-radius:10px;">
            <p style="margin-top:10px; font-weight:bold;">{{ $seller }}</p>
        </div>
        @endforeach
    </div>

    {{-- PRODUCT GRID --}}
    <div style="padding:20px;">
        <div style="
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:20px;
        ">

            @foreach(range(1,12) as $product)
            <div style="background:white; padding:12px; border-radius:12px; text-align:center;">
                <img src="/images/bike.png" style="width:100%; border-radius:8px;">
                <p style="margin:8px 0 0;">Sepeda VOC</p>
                <strong>Rp. 150.000</strong>
                <div style="font-size:12px; color:#b3550d; margin-top:4px;">● Free Shipping</div>
            </div>
            @endforeach

        </div>
    </div>

</div>

</body>
</html>
