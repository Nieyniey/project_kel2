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
    /* New styling for the main container and fixed header */
    .header-fixed {
        background-color: #FFFEF7; /* Navigation and Right Square Background */
        width: 100%;
        /* REVISION 1: Ensure header is fixed */
        position: fixed; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        height: 60px; /* Define height to calculate content padding */
    }
    .settings-active-link {
        /* FC5801 with 40% opacity */
        background-color: rgba(252, 88, 1, 0.4) !important;
        color: #6C2207 !important; 
        border-radius: 8px;
    }
    
    /* Custom size between btn-sm and default */
    .btn-custom-med {
        padding: 0.375rem 0.8rem; /* Slightly bigger padding */
        font-size: 0.95rem;      /* Slightly larger font */
        border-radius: 0.25rem;  
    }

    /* REVISION 3: Custom styling for the "Yes"/"Create" button */
    .btn-seller-mode {
        background-color: transparent;
        color: #FC5801 !important; /* Text color is the main orange */
        border-color: #FC5801 !important; /* Border is the main orange */
        transition: all 0.2s ease;
    }
    .btn-seller-mode:hover {
        /* On hover, background fills with 40% opacity orange */
        background-color: rgba(252, 88, 1, 0.4) !important;
        color: #6C2207 !important; /* Text color turns dark brown on hover */
        border-color: rgba(252, 88, 1, 0.4) !important; /* Border color changes */
    }
    
    /* REVISION 2: Class for the yellow checkmark */
    .text-active-yellow {
        color: #F3D643 !important;
    }

</style>

{{-- Header: Back Button and Title (Fixed) --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #6C2207;">
                    Settings
                </h5>
            </div>
        </div>
    </div>

{{-- Main Container with new background color --}}
{{-- REVISION 1: Added necessary padding-top to compensate for the fixed header height (60px + 20px margin) --}}
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
                            <h5 class="fw-bold mb-0" style="color: #6C2207;">{{ $user->name ?? 'General User' }}</h5>
                            <small class="text-muted">General User</small>
                        </div>

                        {{-- Navigation Links --}}
                        <div class="list-group list-group-flush">
                            {{-- 1. Personal Information --}}
                            <a href="{{ route('buyer.settings.tab', ['tab' => 'personal-info']) }}" 
                                class="list-group-item list-group-item-action border-0" 
                                style="background-color: transparent; color: #6C2207;">
                                <i class="bi bi-person-fill me-2"></i> Personal Information
                            </a>
                            {{-- 2. Your Orders --}}
                            <a href="{{ route('buyer.settings.tab', ['tab' => 'orders']) }}" 
                                class="list-group-item list-group-item-action border-0" 
                                style="background-color: transparent; color: #6C2207;">
                                <i class="bi bi-box-seam-fill me-2"></i> Your Orders
                            </a>
                            {{-- 3. Seller Page (Highlighted) --}}
                            <a href="{{ route('seller.create.form') }}" 
                            class="list-group-item list-group-item-action border-0 settings-active-link shadow-sm" 
                            style="background-color: transparent;">
                                <i class="bi bi-shop me-2"></i> Seller Page
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

                    {{-- Form to create the seller profile (simplified) --}}
                    <form action="{{ route('seller.register') }}" method="POST">
                        @csrf

                        {{-- Store Name (The only required field) --}}
                        <div class="mb-4">
                            <label for="store_name" class="form-label fw-bold" style="color: #6C2207;">Store Name</label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                                id="store_name" name="store_name" 
                                value="{{ old('store_name') }}" 
                                placeholder="e.g., {{ $user->name }} Store"
                                style="background-color: #e5d8c6; border-color: #d8c8b4; color: #6C2207;">
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Placeholder: Store Descriptions --}}
                        <div class="mb-4">
                            <label for="store_description_ph" class="form-label fw-bold" style="color: #6C2207;">Store Descriptions</label>
                            <textarea class="form-control" id="store_description_ph" rows="6" disabled style="background-color: #e5d8c6; border-color: #d8c8b4; color: #6C2207;">(This field is currently omitted from database storage.)</textarea>
                        </div>

                        {{-- Placeholder: Instagram @ --}}
                        <div class="mb-4">
                            <label for="instagram_ph" class="form-label fw-bold" style="color: #6C2207;">Instagram @</label>
                            <input type="text" class="form-control" id="instagram_ph" value="@prabroroJualGinjai" disabled style="background-color: #e5d8c6; border-color: #d8c8b4; color: #6C2207;">
                        </div>

                        {{-- Action Button --}}
                        <div class="d-flex justify-content-end pt-3">
                            {{-- REVISION 3: Button now uses the 'Yes' button style (orange border, orange hover fill, custom size) --}}
                            <button type="submit" class="btn border px-4 btn-seller-mode btn-custom-med">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection