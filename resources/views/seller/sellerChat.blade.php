<!DOCTYPE html>
<html>
<head>
    <title>Chat Seller</title>
</head>
<body style="background:#252525; font-family:Arial, sans-serif;">

<div style="width:100%; background:white; height:70px; display:flex; align-items:center; padding:0 16px;">
    <a href="#" style="text-decoration:none; color:#ff7a00; font-size:20px;">←</a>
    <span style="margin-left:10px; font-size:20px; font-weight:600; color:#ff7a00;">Chat Seller</span>
</div>

<div style="display:flex; height:90vh;">
    
    {{-- LEFT CONTACT LIST --}}
    <div style="width:28%; background:white; border-right:1px solid #ddd; overflow-y:auto;">
        @foreach($contacts as $c)
        <div style="display:flex; padding:12px; border-bottom:1px solid #eee;">
            <img src="https://via.placeholder.com/50" style="width:50px; height:50px; border-radius:50%; margin-right:10px;">
            <div>
                <p style="margin:0; font-weight:600;">{{ $c['name'] }}</p>
                <p style="margin:0; color:gray;">{{ $c['msg'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- RIGHT CHAT PANEL --}}
    <div style="flex:1; background:#e8deb1; display:flex; flex-direction:column;">
        
        <div style="background:white; height:55px; display:flex; align-items:center; justify-content:center; border-bottom:1px solid #ddd;">
            <strong>TechStore Pro</strong>
        </div>

        <div style="flex:1; padding:20px; overflow-y:auto; text-align:center;">
            <span style="background:white; padding:4px 10px; border-radius:10px; font-size:12px;">Selasa</span>
        </div>

        {{-- MESSAGE INPUT --}}
        <div style="display:flex; background:white; padding:10px; border-top:1px solid #ddd;">
            <input type="text" placeholder="Tulis Pesan" 
                   style="flex:1; padding:10px; border:1px solid #ccc; border-radius:5px;">
            <button style="margin-left:8px; border:none; background:none; font-size:18px;">➤</button>
        </div>

    </div>

</div>

</body>
</html>
