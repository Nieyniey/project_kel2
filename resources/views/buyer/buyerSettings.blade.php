@extends('layouts.app') 

@section('title', 'User Settings - ' . Str::title(str_replace('-', ' ', $activeTab)))

@php
    $user = $user ?? Auth::user(); 
    $isSeller = $isSeller ?? $user->seller()->exists();
    
    $profileImageUrl = $user->profile_photo 
                        ? asset('storage/' . $user->profile_photo) 
                        : asset('placeholder.jpg'); 
@endphp

@section('content')
<style>
    
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
    .settings-active-link {
        background-color: rgba(252, 88, 1, 0.4) !important;
        color: #6C2207 !important; 
        border-radius: 8px;
    }
    
    .btn-custom-med {
        padding: 0.375rem 0.8rem; 
        font-size: 0.95rem;      
        border-radius: 0.25rem;  
    }

    .btn-seller-mode-custom {
        background-color: #FFFEF7 !important; 
        color: #f79471 !important; 
        border: 2px solid #f79471 !important; 
        transition: all 0.2s ease;
    }

    .btn-seller-mode-custom:hover {
        background-color: #f79471 !important; 
        color: #FFFEF7 !important; 
        border-color: #f79471 !important; 
    }

    .save-button-right {
        float: right;
    }
    
    .custom-form-control {
        background-color: #e5d8c6 !important; 
        border-color: #d8c8b4 !important; 
        color: #6C2207 !important; 
    }

    .fixed-panel-left {
        position: fixed;
        padding-bottom: 20px;
        padding-left: 20px;
        z-index: 1030; 
        overflow-y: auto; 
    }

    @media (max-width: 991.98px) {
        .fixed-panel-left {
            width: 33.3333%; 
        }
    }
</style>

{{-- Header --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('homeIn') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #FC5801!important;">
                    Pengaturan
                </h5>
            </div>
        </div>
    </div>

{{-- Main Container --}}
<div class="container-fluid py-4" style="background-color: #E8E0BB; color: #6C2207; min-height: 100vh; padding-top: 20px !important;">
    <div class="container mt-4">
        <div class="row g-4">
            {{-- Left Panel: Navigation --}}
            <div class="col-md-4 col-lg-3">
                <div class="fixed-panel-left d-none d-md-block">
                    <div class="card shadow-sm border-0" style="background-color: #FFFEF7;">
                        <div class="card-body p-4">
                            {{-- User Info Header --}}
                            <div class="d-flex flex-column align-items-center mb-4">
                                <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                                    <img src="{{ $profileImageUrl }}" 
                                            class="w-100 h-100 object-fit-cover rounded-circle">
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #6C2207;">{{ $user->name ?? 'User' }}</h5>
                                <small class="text-muted">User</small>
                            </div>

                            {{-- Navigation Links --}}
                            <div class="list-group list-group-flush">
                                {{-- Personal Information --}}
                                <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" 
                                    class="list-group-item list-group-item-action border-0 {{ $activeTab == 'personal-info' ? 'settings-active-link shadow-sm' : '' }}" 
                                    style="background-color: transparent; color: #6C2207;">
                                    <i class="bi bi-person-fill me-2"></i> Informasi Personal
                                </a>
                                {{-- Your Orders --}}
                                <a href="{{ route('buyer.settings', ['tab' => 'orders']) }}" 
                                    class="list-group-item list-group-item-action border-0 {{ $activeTab == 'orders' ? 'settings-active-link shadow-sm' : '' }}" 
                                    style="background-color: transparent; color: #6C2207;">
                                    <i class="bi bi-box-seam-fill me-2"></i> Pesanan
                                </a>
                                {{-- Seller Page --}}
                                <a href="{{ route('buyer.settings', ['tab' => 'seller-mode']) }}" 
                                    class="list-group-item list-group-item-action border-0 {{ $activeTab == 'seller-mode' ? 'settings-active-link shadow-sm' : '' }}" 
                                    style="background-color: transparent; color: #6C2207;">
                                    <i class="bi bi-shop me-2"></i> Mode Penjual
                                </a>
                                
                                {{-- Log Out --}}
                                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action mt-3 border-0" style="background-color: transparent; color: #6C2207;">
                                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Panel: Dynamic Content --}}
            <div class="col-md-8 col-lg-9">
                <div class="card shadow-sm border-0 p-4" style="background-color: #FFFEF7;">
                    
                    @if ($activeTab == 'personal-info')
                        <h3 class="fw-bold mb-4" style="color: #6C2207;">Informasi Personal</h3>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            {{-- Show a general error message if there are any validation issues --}}
                            <div class="alert alert-danger">Periksa kembali untuk error.</div>
                        @endif

                        <form action="{{ route('buyer.settings.update.personal') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- Username --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold" style="color: #6C2207;">Username</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror custom-form-control" 
                                        id="name" name="name" 
                                        value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold" style="color: #6C2207;">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror custom-form-control" 
                                        id="email" name="email" 
                                        value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Phone Number --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold" style="color: #6C2207;">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror custom-form-control" 
                                        id="phone" name="phone" 
                                        value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DOB --}}
                            <div class="mb-3">
                                <label for="DOB" class="form-label fw-bold" style="color: #6C2207;">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('DOB') is-invalid @enderror custom-form-control" 
                                        id="DOB" name="DOB" 
                                        value="{{ old('DOB', $user->DOB) }}">
                                @error('DOB')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Profile Photo --}}
                            <div class="mb-4 p-3 border rounded" style="background-color: #f7f3ed; border-color: #d8c8b4;">
                                <label class="form-label fw-bold mb-3" style="color: #6C2207;">Foto Profil</label>

                                <div class="d-flex align-items-center">
                                    
                                    {{-- 1. Profile Image Area (Circular) --}}
                                    <div class="rounded-circle me-4 d-flex justify-content-center align-items-center" 
                                        style="width: 85px; height: 85px; overflow: hidden; border: 1px solid rgba(252, 88, 1, 0.4); flex-shrink: 0;">
                                        
                                        @if($user->profile_photo)
                                            <img id="profile-image-preview" 
                                                src="{{ asset('storage/' . $user->profile_photo) }}" 
                                                class="w-100 h-100 object-fit-cover">
                                        @else
                                            <img src="{{ $profileImageUrl }}" 
                                                class="w-100 h-100 object-fit-cover">
                                        @endif
                                    </div>

                                    {{-- 2. File Input Area --}}
                                    <div class="flex-grow-1">
                                        <input type="file" 
                                            class="form-control @error('profile_photo') is-invalid @enderror custom-form-control" 
                                            id="profile_photo_input" 
                                            name="profile_photo" 
                                            accept="image/jpeg, image/png"> {{-- Corrected accept to 'image/jpeg' --}}
                                        <p class="text-muted mb-2 small">Masukan foto baru (Maks. 2MB, hanya jpeg/png/jpg/gif).</p>

                                        @error('profile_photo')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-3">
                                <button type="submit" class="btn px-4 btn-seller-mode-custom">Simpan</button>
                            </div>
                        </form>
                        
                    @elseif ($activeTab == 'orders')
                        <h3 class="fw-bold mb-4" style="color: #6C2207;">Pesanan</h3>

                        {{-- Alert Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                        {{-- Main Order List --}}
                        @if ($orders->isEmpty())
                            <p class="text-muted">Belum ada pesanan.</p>
                        @else
                            @foreach ($orders as $order)
                                @php
                                    $firstItem = $order->items->first();
                                    $seller = $firstItem->product->seller ?? null;
                                    $sellerName = $seller->store_name ?? 'Seller Not Found';
                                    $sellerId = $seller->user_id ?? 0;
                                @endphp

                                {{-- INDIVIDUAL ORDER CARD (This provides the background/boundary for ONE order) --}}
                                <div class="card p-3 mb-4" style="border-color: #d8c8b4;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold mb-0" style="color: #6C2207;">{{ $sellerName }}</h5> 
                                        <span class="badge rounded-pill text-dark" style="background-color: rgba(252, 88, 1, 0.4) !important; color: #6C2207 !important;">{{ ucfirst($order->status) ?? 'Pending' }}</span>
                                    </div>
                                    
                                    <hr class="my-2" style="border-color: #d8c8b4;">

                                    @foreach ($order->items as $item)
                                        @php 
                                            $product = $item->product;
                                        @endphp

                                        <div class="d-flex mb-3">
                                            {{-- Product Image Section --}}
                                            <div style="width: 80px; height: 80px; flex-shrink: 0; background-color: #fff; border-radius: 5px; overflow: hidden; border: 1px solid #eee;" class="me-3 d-flex align-items-center justify-content-center">
                                                @if ($product->image_path)
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                        class="img-fluid"
                                                        style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                @else
                                                    <i class="bi bi-image" style="font-size: 1.5rem; color: #ccc;"></i>
                                                @endif
                                            </div>

                                            {{-- Product Info Section --}}
                                            <div>
                                                <strong class="d-block" style="color: #6C2207;">{{ $product->name }}</strong>
                                                <small class="d-block" style="color: #6C2207;">x {{ $item->qty }}</small>
                                            </div>
                                        </div>
                                    @endforeach

                                    <hr class="my-3" style="border-color: #d8c8b4;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($sellerId)
                                                <a href="{{ route('chat.show', $sellerId) }}" 
                                                    class="btn btn-sm" style="color: #6C2207; border-color: #6C2207;">
                                                    Chat Penjual
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">Seller chat unavailable</span>
                                            @endif

                                            {{-- Action Buttons: PENDING/PAID STATUS --}}
                                            @if ($order->status == 'pending')
                                                {{-- Button: CANCEL (The only action while pending) --}}
                                                <form action="{{ route('order.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Cancel
                                                    </button>
                                                </form>

                                            @elseif ($order->status == 'paid') 
                                                {{-- Button: COMPLETED (The only action while paid, before shipping) --}}
                                                <form action="{{ route('order.complete', $order) }}" method="POST" onsubmit="return confirm('Confirm receipt of order #{{ $order->order_id }}?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Selesai
                                                    </button>
                                                </form>
                                            
                                            {{-- Action Button: COMPLETED/CANCELLED STATUS --}}
                                            @elseif (in_array($order->status, ['completed', 'cancelled']))
                                                {{-- Button: DELETE --}}
                                                <form action="{{ route('order.delete', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order history? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                            
                                        </div>
                                        {{-- END: Action Button Group --}}
                                        
                                        {{-- Total price (Pushed to the far right using justify-content-between on the parent) --}}
                                        <p class="mb-0 fw-bold" style="color: #6C2207;">Total: <span style="color: #FC5801 !important;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>

                                    </div>
                                </div>
                            @endforeach
                        @endif

                    @elseif ($activeTab == 'seller-mode')
                        <h3 class="fw-bold mb-4" style="color: #6C2207;">Mode Penjual</h3>

                        @if($isSeller)
                            @php
                                $storeName = $user->seller->store_name ?? 'Your Store';
                            @endphp
                            <p class="lead" style="color: #6C2207;">Berubah ke penjual {{ $storeName }}?</p>
                            <form action="{{ route('seller.settings') }}" method="GET">
                                <button type="submit" class="btn px-4 btn-seller-mode-custom">Yes</button>
                            </form>
                        @else
                            <p class="lead" style="color: #6C2207;">Anda belum memiliki toko. Apakah anda ingin menjadi penjual?</p>
                            <form action="{{ route('seller.create.form') }}" method="GET">
                                <button type="submit" class="btn px-4 btn-seller-mode-custom">Yes</button>
                            </form>
                        @endif

                    @else
                        <div class="alert alert-warning" style="color: #6C2207; background-color: #e5d8c6; border-color: #d8c8b4;">
                            Could not load settings content. Please select a valid tab.
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection