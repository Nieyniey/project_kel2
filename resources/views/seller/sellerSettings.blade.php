@extends('layouts.app') 

@section('title', 'Seller Settings')

@php
    $user = Auth::user();
    $seller = optional($user)->seller; 
    
    $profileImageUrl = $user->profile_photo 
                        ? asset('storage/' . $user->profile_photo) 
                        : asset('placeholder.jpg');
    
    $activeTab = request('tab') ?? 'store-info'; 
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
</style>

{{-- Header --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('seller.products') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #6C2207;">
                    Settings
                </h5>
            </div>
        </div>
    </div>

<div class="container-fluid py-4" style="background-color: #E8E0BB; color: #6C2207; min-height: 100vh; padding-top: 20px !important;">
  <div class="container mt-4">
    <div class="row g-4">
        {{-- Left Panel: Navigation --}}
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm border-0" style="background-color: #FFFEF7;">
                <div class="card-body p-4" style="color: #6C2207;">
                    {{-- Store Info Header --}}
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                            <img src="{{ $profileImageUrl }}" 
                                class="w-100 h-100 object-fit-cover rounded-circle">
                        </div>
                        <h5 class="fw-bold mb-0">{{ $seller->store_name ?? 'Your Store' }}</h5>
                        <h5> </h5>
                        <small class="text-muted">Seller</small>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="list-group list-group-flush">
                        
                        {{-- 1. Store Information (Default Tab) --}}
                        <a href="{{ route('seller.settings', ['tab' => 'store-info']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'store-info' ? 'seller-active-link shadow-sm' : '' }}" 
                          style="background-color: transparent; color: #6C2207;">
                            <i class="bi bi-shop me-2"></i> Store Information
                        </a>
                        
                        {{-- 2. Seller Orders --}}
                        <a href="{{ route('seller.settings', ['tab' => 'orders']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'orders' ? 'seller-active-link shadow-sm' : '' }}" 
                          style="background-color: transparent; color: #6C2207;">
                            <i class="bi bi-box-seam-fill me-2"></i> Seller Orders
                        </a>
                        
                        {{-- 3. User Page (Switch to Buyer Settings) --}}
                        <a href="{{ route('seller.settings', ['tab' => 'user-page']) }}" 
                            class="list-group-item list-group-item-action border-0 {{ $activeTab == 'user-page' ? 'seller-active-link shadow-sm' : '' }}" 
                            style="background-color: transparent; color: #6C2207;">
                                <i class="bi bi-person-circle me-2"></i> User Page
                        </a>
                        
                        {{-- Log Out --}}
                        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action mt-3 border-0" style="background-color: transparent; color: #6C2207;">
                            <i class="bi bi-box-arrow-right me-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Dynamic Content --}}
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-sm border-0 p-4" style="background-color: #FFFEF7; color: #6C2207;">
                 
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
                    
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Store Status</label>
                            <select class="form-select @error('status') is-invalid @enderror custom-form-control" 
                                    id="status" 
                                    name="status"
                                    aria-describedby="statusHelp">
                            
                                @php $currentStatus = old('status', $seller->status ?? 'inactive'); @endphp

                                <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>
                                    Active (Open for business)
                                </option>
                                <option value="inactive" {{ $currentStatus == 'inactive' ? 'selected' : '' }}>
                                    Closed (Temporarily unavailable)
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small id="statusHelp" class="form-text text-muted">
                                Setting your store to Closed will hide all your products from buyers.
                            </small>
                        </div>
                    
                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn px-4 btn-seller-mode-custom">Save Changes</button>
                        </div>
                    </form>
                
                @elseif ($activeTab == 'orders')
                    <h3 class="fw-bold mb-4">Seller Orders</h3>
                    <div class="alert alert-info">
                        This section is where you would list and manage all orders placed by customers, showing their status (Pending, Packing, Shipped, etc.).
                    </div>
                
                @elseif ($activeTab == 'user-page')
                    <h3 class="fw-bold mb-4" style="color: #6C2207;">Switch to Buyer Mode</h3>
                    
                    @php
                        $displayName = $user->name ?? ($user->seller->store_name ?? 'Your User Account');
                    @endphp
                    
                    <p class="lead" style="color: #6C2207;">
                        You are currently managing your {{ $user->seller->store_name ?? 'Store' }} settings. Click below to switch to managing your personal account and buyer orders.
</p>

                    <form action="{{ route('buyer.settings') }}" method="GET">
                        <button type="submit" 
                            class="btn px-4 btn-seller-mode-custom">
                            Yes
                        </button>
                    </form>
                
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
  </div>  
</div>
@endsection