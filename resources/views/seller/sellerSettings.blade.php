@extends('layouts.app') 

@section('title', 'Seller Settings')

@php
    // --- Data Initialization ---
    // Ensure $seller is available (using the relationship from the authenticated user)
    $user = Auth::user();
    // Use optional() chaining for safety, though the controller should guarantee $seller exists
    $seller = optional($user)->seller; 
    
    // Set a default profile image for the seller/user header
    $profileImageUrl = $user->profile_photo 
                        ? asset('storage/' . $user->profile_photo) 
                        : asset('placeholder.jpg');
    
    // Determine the active tab from the query string or default to 'store-info'
    $activeTab = request('tab') ?? 'store-info'; 
@endphp

@section('content')
<style>
    /* Custom style for the active tab link */
    .seller-active-link {
        background-color: #f79471 !important; 
        color: white !important;
        border-radius: 8px;
    }
    .custom-form-control {
        background-color: #e5d8c6; 
        border-color: #d8c8b4; 
        color: #6C2207;
    }
</style>

<div class="container py-5" style="background-color: #f8f8f8;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('home') }}" class="text-dark me-3" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Seller Settings</h2>
    </div>

    <div class="row g-4">
        {{-- Left Panel: Navigation --}}
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm border-0" style="background-color: #f7e6d1;">
                <div class="card-body p-4">
                    {{-- Store Info Header --}}
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                            <img src="{{ $profileImageUrl }}" 
                                alt="{{ $seller->store_name ?? 'Your Store' }} Logo" 
                                class="w-100 h-100 object-fit-cover rounded-circle">
                        </div>
                        <h5 class="fw-bold mb-0">{{ $seller->store_name ?? 'Your Store' }}</h5>
                        <small class="text-muted">Seller</small>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="list-group list-group-flush">
                        
                        {{-- 1. Store Information (Default Tab) --}}
                        <a href="{{ route('seller.settings', ['tab' => 'store-info']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'store-info' ? 'seller-active-link shadow-sm' : '' }}" 
                          style="background-color: transparent;">
                            <i class="bi bi-shop me-2"></i> Store Information
                        </a>
                        
                        {{-- 2. Seller Orders --}}
                        <a href="{{ route('seller.settings', ['tab' => 'orders']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'orders' ? 'seller-active-link shadow-sm' : '' }}" 
                          style="background-color: transparent;">
                            <i class="bi bi-box-seam-fill me-2"></i> Seller Orders
                        </a>
                        
                        {{-- 3. User Page (Switch to Buyer Settings) --}}
                        <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'user-page' ? 'seller-active-link shadow-sm' : '' }}" 
                          style="background-color: transparent;">
                            <i class="bi bi-person-circle me-2"></i> User Page
                        </a>
                        
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
                 
                {{-- Global Feedback Messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- ====================================== --}}
                {{-- 1. STORE INFORMATION TAB CONTENT --}}
                {{-- ====================================== --}}
                @if ($activeTab == 'store-info')
                    <h3 class="fw-bold mb-4">Store Information</h3>

                    <form action="{{ route('seller.settings.update.store') }}" method="POST">
                        @csrf
                    
                        {{-- Store Name --}}
                        <div class="mb-4">
                            <label for="store_name" class="form-label fw-bold">Store Name</label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror custom-form-control" 
                                    id="store_name" name="store_name" 
                                    value="{{ old('store_name', $seller->store_name) }}" 
                                    placeholder="Enter your store name">
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Store Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror custom-form-control" 
                                    id="description" name="description" rows="4"
                                    placeholder="Tell buyers about your store and products.">{{ old('description', $seller->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        {{-- Instagram @ --}}
                        <div class="mb-4">
                            <label for="instagram" class="form-label fw-bold">Instagram @</label>
                            <input type="text" class="form-control @error('instagram') is-invalid @enderror custom-form-control" 
                                    id="instagram" name="instagram" 
                                    value="{{ old('instagram', $seller->instagram) }}"
                                    placeholder="@your_instagram_handle">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        {{-- Status (Display only - set in backend) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-static fw-bold text-success">{{ ucfirst($seller->status ?? 'pending') }}</p>
                            <small class="text-muted">Your store status is managed by the system.</small>
                        </div>
                    
                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                
                {{-- ====================================== --}}
                {{-- 2. SELLER ORDERS TAB CONTENT --}}
                {{-- ====================================== --}}
                @elseif ($activeTab == 'orders')
                    <h3 class="fw-bold mb-4">Seller Orders</h3>
                    <div class="alert alert-info">
                        This section is where you would list and manage all orders placed by customers, showing their status (Pending, Packing, Shipped, etc.).
                    </div>
                    
                {{-- ====================================== --}}
                {{-- 3. USER PAGE TAB CONTENT --}}
                {{-- ====================================== --}}
                @elseif ($activeTab == 'user-page')
                    <h3 class="fw-bold mb-4">Switch to User (Buyer) Mode</h3>
                    <div class="alert alert-warning">
                        This tab serves as a direct link back to your **Buyer Settings**. Click the button below to manage your personal information, addresses, and buyer orders.
                    </div>
                    <a href="{{ route('buyer.settings', ['tab' => 'personal-info']) }}" class="btn btn-lg" style="background-color: #f79471; color: white;">
                        Go to Buyer Settings
                    </a>
                
                @else
                    {{-- Fallback for invalid tab --}}
                    <div class="alert alert-warning">
                        The requested tab content is not available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection