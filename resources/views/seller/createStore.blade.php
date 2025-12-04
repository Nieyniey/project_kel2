@extends('layouts.Main') 

@section('title', 'User Settings - Create Store')

@php
    $user = $user ?? Auth::user(); 
    $profileImageUrl = $user->profile_photo 
                       ? asset('storage/' . $user->profile_photo) 
                       : asset('placeholder.jpg');
    $activeTab = 'seller-mode'; 
@endphp

@section('content')
<div class="container py-5" style="background-color: #f8f8f8;">
    {{-- Header: Back Button and Title --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('buyer.settings', ['tab' => 'seller-mode']) }}" class="text-dark me-3" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left"></i> 
        </a>
        <h2 class="fw-bold mb-0">Settings</h2>
    </div>

    <div class="row g-4">
        {{-- Left Panel: Navigation (Copied from buyerSettings to maintain look) --}}
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm border-0" style="background-color: #f7e6d1;">
                <div class="card-body p-4">
                    {{-- User Info Header (Praboro Widodo, General User) --}}
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                            <img src="{{ $profileImageUrl }}" 
                                 alt="{{ $user->name }}'s Profile Picture" 
                                 class="w-100 h-100 object-fit-cover rounded-circle">
                        </div>
                        <h5 class="fw-bold mb-0">{{ $user->name ?? 'General User' }}</h5>
                        <small class="text-muted">General User</small>
                    </div>

                    {{-- Navigation Links (Address Lists Removed) --}}
                    <div class="list-group list-group-flush">
                        {{-- 1. Personal Information --}}
                        <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
                            <i class="bi bi-person-fill me-2"></i> Personal Information
                        </a>
                        {{-- 2. Your Orders --}}
                        <a href="{{ route('buyer.settings', ['tab' => 'orders']) }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
                            <i class="bi bi-box-seam-fill me-2"></i> Your Orders
                        </a>
                        {{-- 3. Seller Page (Highlighted because we are in the seller creation flow) --}}
                        <a href="{{ route('seller.create.form') }}" 
                           class="list-group-item list-group-item-action border-0 active shadow-sm" 
                           style="background-color: #f79471; color: white; border-radius: 8px;">
                            <i class="bi bi-shop me-2"></i> Seller Page
                        </a>
                        
                        {{-- Log Out --}}
                        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action mt-3 border-0" style="background-color: transparent;">
                            <i class="bi bi-box-arrow-right me-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Create Store Content --}}
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-sm border-0 p-4" style="background-color: #f7e6d1;">
                
                <h3 class="fw-bold mb-4">Create Store</h3>
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Form to create the seller profile (simplified) --}}
                <form action="{{ route('seller.register') }}" method="POST">
                    @csrf

                    {{-- Store Name (The only required field) --}}
                    <div class="mb-4">
                        <label for="store_name" class="form-label fw-bold">Store Name</label>
                        <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                               id="store_name" name="store_name" 
                               value="{{ old('store_name') }}" 
                               placeholder="e.g., {{ $user->name }} Store"
                               style="background-color: #e5d8c6; border-color: #d8c8b4;">
                        @error('store_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Placeholder: Store Descriptions (Removed content based on request) --}}
                    <div class="mb-4">
                        <label for="store_description_ph" class="form-label fw-bold">Store Descriptions</label>
                        <textarea class="form-control" id="store_description_ph" rows="6" disabled style="background-color: #e5d8c6; border-color: #d8c8b4;">(This field is currently omitted from database storage.)</textarea>
                    </div>

                    {{-- Placeholder: Instagram @ (Removed content based on request) --}}
                    <div class="mb-4">
                        <label for="instagram_ph" class="form-label fw-bold">Instagram @</label>
                        <input type="text" class="form-control" id="instagram_ph" value="@prabroroJualGinjai" disabled style="background-color: #e5d8c6; border-color: #d8c8b4;">
                    </div>

                    {{-- Placeholder: Set Store Status (Removed content based on request) --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Set Store Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="store_status_ph" id="statusActive" value="active" checked disabled>
                                <label class="form-check-label" for="statusActive">
                                    Active <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5" style="background-color: #f79471; border-color: #f79471;">Publish Store</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection