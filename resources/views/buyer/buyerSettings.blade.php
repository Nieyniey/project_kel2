@extends('layouts.Main') 

@section('title', 'User Settings - ' . Str::title(str_replace('-', ' ', $activeTab)))

@php
    $user = $user ?? Auth::user(); 
    $isSeller = $isSeller ?? $user->seller()->exists();
    
    $formattedDOB = $user->DOB ? \Carbon\Carbon::parse($user->DOB)->format('d/m/Y') : '';
    
    $profileImageUrl = $user->profile_photo 
                       ? asset('storage/' . $user->profile_photo) 
                       : asset('placeholder.jpg'); 
@endphp

@section('content')
<div class="container py-5" style="background-color: #f8f8f8;">
    {{-- Header: Back Button and Title --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('home') }}" class="text-dark me-3" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left"></i> 
        </a>
        <h2 class="fw-bold mb-0">Settings</h2>
    </div>

    <div class="row g-4">
        {{-- Left Panel: Navigation --}}
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm border-0" style="background-color: #f7e6d1;">
                <div class="card-body p-4">
                    {{-- User Info Header --}}
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                            <img src="{{ $profileImageUrl }}" 
                                 alt="{{ $user->name }}'s Profile Picture" 
                                 class="w-100 h-100 object-fit-cover rounded-circle">
                        </div>
                        <h5 class="fw-bold mb-0">{{ $user->name ?? 'General User' }}</h5>
                        <small class="text-muted">General User</small>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="list-group list-group-flush">
                        {{-- 1. Personal Information --}}
                        <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" 
                           class="list-group-item list-group-item-action border-0 {{ $activeTab == 'personal-info' ? 'active shadow-sm' : '' }}" 
                           style="{{ $activeTab == 'personal-info' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}">
                            <i class="bi bi-person-fill me-2"></i> Personal Information
                        </a>
                        {{-- 2. Your Orders --}}
                        <a href="{{ route('buyer.settings', ['tab' => 'orders']) }}" 
                           class="list-group-item list-group-item-action border-0 {{ $activeTab == 'orders' ? 'active shadow-sm' : '' }}" 
                           style="{{ $activeTab == 'orders' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}">
                            <i class="bi bi-box-seam-fill me-2"></i> Your Orders
                        </a>
                        {{-- 3. Seller Page --}}
                        @if($isSeller)
                            <a href="{{ route('seller.products') }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
                                <i class="bi bi-shop me-2"></i> Seller Page
                            </a>
                        @endif
                        
                        {{-- Log Out --}}
                        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action mt-3 border-0" style="background-color: transparent;">
                            <i class="bi bi-box-arrow-right me-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Dynamic Content --}}
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-sm border-0 p-4" style="background-color: #f7e6d1;">
                
                @if ($activeTab == 'personal-info')
                    {{-- ---------------------------------------- --}}
                    {{-- CONTENT: PERSONAL INFORMATION --}}
                    {{-- ---------------------------------------- --}}
                    <h3 class="fw-bold mb-4">Personal Information</h3>

                    {{-- Feedback Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">Please check the form for errors.</div>
                    @endif

                    <form action="{{ route('buyer.settings.update.personal') }}" method="POST">
                        @csrf

                        {{-- Username (Mapped to 'name') --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" style="background-color: #e5d8c6; border-color: #d8c8b4;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $user->email) }}" style="background-color: #e5d8c6; border-color: #d8c8b4;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $user->phone) }}" style="background-color: #e5d8c6; border-color: #d8c8b4;">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date of Birth (DOB) --}}
                        <div class="mb-4">
                            <label for="DOB" class="form-label fw-bold">Date Of Birth</label>
                            <input type="text" class="form-control @error('DOB') is-invalid @enderror" 
                                   id="DOB" name="DOB" placeholder="DD/MM/YYYY"
                                   value="{{ old('DOB', $formattedDOB) }}" style="background-color: #e5d8c6; border-color: #d8c8b4;">
                            @error('DOB')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="reset" class="btn btn-outline-secondary px-4">Discard Changes</button>
                            <button type="submit" class="btn btn-primary px-4" style="background-color: #f79471; border-color: #f79471;">Save Changes</button>
                        </div>
                    </form>

                @elseif ($activeTab == 'orders')
                    {{-- ---------------------------------------- --}}
                    {{-- CONTENT: YOUR ORDERS --}}
                    {{-- ---------------------------------------- --}}
                    <h3 class="fw-bold mb-4">Order List</h3>
                    
                    @if ($orders->isEmpty())
                        <div class="alert alert-info">You have no orders yet.</div>
                    @else
                        @foreach ($orders as $order)
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-body">
                                
                                {{-- Get Seller Name and ID from the first item in the order --}}
                                @php
                                    $firstItem = $order->items->first();
                                    $seller = $firstItem->product->seller ?? null;
                                    $sellerName = $seller->store_name ?? 'Seller Not Found';
                                    $sellerId = $seller->user_id ?? 0; // Seller's user ID for chat
                                @endphp

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0">{{ $sellerName }}</h5> 
                                    <span class="badge rounded-pill text-bg-warning text-dark">{{ $order->status ?? 'Pending' }}</span>
                                </div>
                                
                                <hr class="my-2">
                                
                                {{-- Loop through all items in this specific order --}}
                                @foreach ($order->items as $item) 
                                    @php
                                        $product = $item->product; 
                                        $itemSubtotal = $item->quantity * $product->price; 
                                    @endphp
                                    <div class="row align-items-center mb-2">
                                        <div class="col-3 col-lg-2">
                                            {{-- Display Product Image (placeholder for now) --}}
                                            [Image of {{ $product->name ?? 'Product' }}]

                                        </div>
                                        <div class="col-9 col-lg-7">
                                            <p class="mb-1 fw-bold">{{ $product->name ?? 'Product Not Found' }}</p>
                                            <small class="text-muted">x {{ $item->quantity }}</small>
                                        </div>
                                        <div class="col-12 col-lg-3 text-lg-end pt-2 pt-lg-0">
                                            <p class="mb-0 text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach {{-- End of Order Items loop --}}

                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    {{-- Chat button linked to the seller's user ID --}}
                                    <a href="{{ route('chat.show.user', ['receiverId' => $sellerId]) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        Chat Penjual
                                    </a>
                                    
                                    {{-- Total price from the Order model --}}
                                    <p class="mb-0 fw-bold">Total: <span class="text-danger">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif

                @elseif ($activeTab == 'seller-mode')
                    {{-- ---------------------------------------- --}}
                    {{-- CONTENT: SELLER MODE HANDLER (NEW LOGIC) --}}
                    {{-- ---------------------------------------- --}}
                    <h3 class="fw-bold mb-4">Change to Seller Mode</h3>

                    @if($isSeller)
                        {{-- NEW: If the user IS a seller, show the confirmation prompt (image_772f9b.jpg) --}}
                        @php
                            $storeName = $user->seller->store_name ?? 'Your Store';
                        @endphp
                        
                        <p class="lead">Transform into **{{ $storeName }}** Seller?</p>
                        
                        {{-- The 'Yes' button should be a link/form that redirects to the seller settings --}}
                        <form action="{{ route('seller.settings') }}" method="GET">
                            <button type="submit" class="btn btn-light btn-lg border px-5" style="border-color: #d8c8b4;">Yes</button>
                        </form>
                    @else
                        {{-- OLD: If the user is NOT a seller, show the registration prompt (image_8133a9.jpg) --}}
                        <p class="lead">You don't have a store yet. Would you like to become a seller?</p>
                        <form action="{{ route('seller.create.form') }}" method="GET">
                            <button type="submit" class="btn btn-light btn-lg border px-5" style="border-color: #d8c8b4;">Yes</button>
                        </form>
                    @endif
                
                @else
                    {{-- Fallback for an unrecognized tab --}}
                    <div class="alert alert-warning">
                        Could not load settings content. Please select a valid tab.
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection