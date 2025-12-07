@extends('layouts.app') 

@section('title', 'User Settings - Create Store')

@php
    $user = $user ?? Auth::user(); 
    $profileImageUrl = $user->profile_photo 
                        ? asset('storage/' . $user->profile_photo) 
                        : asset('placeholder.jpg');
    $activeTab = 'seller-mode'; 
@endphp

@section('content')
<style>
    .header-fixed {
        background-color: #FFFEF7;
        width: 100%;
        position: fixed; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        height: 60px;
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
    .btn-seller-mode {
        background-color: transparent;
        color: #FC5801 !important;
        border-color: #FC5801 !important;
        transition: all 0.2s ease;
    }
    .btn-seller-mode:hover {
        background-color: rgba(252, 88, 1, 0.4) !important;
        color: #6C2207 !important;
        border-color: rgba(252, 88, 1, 0.4) !important;
    }
    .text-active-yellow {
        color: #F3D643 !important;
    }
    .custom-form-control {
        background-color: #e5d8c6; 
        border-color: #d8c8b4; 
        color: #6C2207;
    }

</style>

{{-- Header --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('homeIn') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #6C2207;">
                    Pengaturan
                </h5>
            </div>
        </div>
    </div>

{{-- Main Container with new background color --}}
<div class="container-fluid py-4" style="background-color: #E8E0BB; color: #6C2207; min-height: 100vh; padding-top: 80px !important;">
    <div class="container mt-4">
        <div class="row g-4">
            {{-- Left Panel: Navigation --}}
            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm border-0" style="background-color: #FFFEF7;">
                    <div class="card-body p-4">
                        {{-- User Info Header --}}
                        <div class="d-flex flex-column align-items-center mb-4">
                            <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                                <img src="{{ $profileImageUrl }}" 
                                            alt="{{ $user->name }}'s Profile Picture" 
                                            class="w-100 h-100 object-fit-cover rounded-circle">
                            </div>
                            <h5 class="fw-bold mb-0" style="color: #6C2207;">{{ $user->name ?? 'User' }}</h5>
                            <small class="text-muted">User</small>
                        </div>

                        {{-- Navigation Links --}}
                        <div class="list-group list-group-flush">
                            {{-- 1. Personal Information --}}
                            <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" 
                                class="list-group-item list-group-item-action border-0" 
                                style="background-color: transparent; color: #6C2207;">
                                <i class="bi bi-person-fill me-2"></i> Informasi Personal
                            </a>
                            {{-- 2. Your Orders --}}
                            <a href="{{ route('buyer.settings', ['tab' => 'orders']) }}" 
                                class="list-group-item list-group-item-action border-0" 
                                style="background-color: transparent; color: #6C2207;">
                                <i class="bi bi-box-seam-fill me-2"></i> Pesanan
                            </a>
                            {{-- 3. Seller Page (Highlighted) --}}
                            <a href="{{ route('seller.create.form') }}" 
                            class="list-group-item list-group-item-action border-0 settings-active-link shadow-sm" 
                            style="background-color: transparent;">
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

            {{-- Right Panel: Create Store Content --}}
            <div class="col-md-8 col-lg-9">
                <div class="card shadow-sm border-0 p-4" style="background-color: #FFFEF7;">
                    
                    <h3 class="fw-bold mb-4" style="color: #6C2207;">Create Store</h3>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Form to create the seller profile --}}
                    <form action="{{ route('seller.register') }}" method="POST">
                        @csrf

                        {{-- 1. Store Name --}}
                        <div class="mb-4">
                            <label for="store_name" class="form-label fw-bold" style="color: #6C2207;">Nama Toko<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror custom-form-control" 
                                id="store_name" name="store_name" 
                                value="{{ old('store_name') }}" 
                                placeholder="e.g., {{ $user->name }} Store">
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Description (Now a functional textarea) --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold" style="color: #6C2207;">Deskripsi Toko</label>
                            <textarea class="form-control @error('description') is-invalid @enderror custom-form-control" 
                                id="description" name="description" rows="4"
                                placeholder="Tell buyers a little about your store, products, and mission.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Instagram @ (Now a functional input) --}}
                        <div class="mb-4">
                            <label for="instagram" class="form-label fw-bold" style="color: #6C2207;">Instagram @ (Opsional)</label>
                            <input type="text" class="form-control @error('instagram') is-invalid @enderror custom-form-control" 
                                id="instagram" name="instagram" 
                                value="{{ old('instagram') }}"
                                placeholder="@your_instagram_handle">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Action Button --}}
                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn border px-4 btn-seller-mode btn-custom-med">Buat Toko</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection