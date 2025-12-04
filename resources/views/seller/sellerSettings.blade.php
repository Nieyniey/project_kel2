@extends('layouts.main') 

@section('title', 'Seller Settings')

@php
    // Ensure $seller is available
    $user = Auth::user();
    $seller = $seller ?? $user->seller;
    // Update: Check for 'pickup-addresses' as the default if a specific tab is requested
    $activeTab = $activeTab ?? 'store-info'; 
@endphp

@section('content')
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
                                
                        </div>
                        <h5 class="fw-bold mb-0">{{ $seller->store_name ?? 'Your Store' }}</h5>
                        <small class="text-muted">Seller</small>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="list-group list-group-flush">
                        {{-- Store Information --}}
                        <a href="{{ route('seller.settings', ['tab' => 'store-info']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'store-info' ? 'active shadow-sm' : '' }}" 
                          style="{{ $activeTab == 'store-info' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}"
                          aria-current="{{ $activeTab == 'store-info' ? 'true' : 'false' }}">
                            <i class="bi bi-shop me-2"></i> Store Information
                        </a>
                        
                        {{-- Pick Up Addresses (UPDATED LINK) --}}
                        <a href="{{ route('seller.settings.tab', ['tab' => 'pickup-addresses']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'pickup-addresses' ? 'active shadow-sm' : '' }}" 
                          style="{{ $activeTab == 'pickup-addresses' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}"
                          aria-current="{{ $activeTab == 'pickup-addresses' ? 'true' : 'false' }}">
                            <i class="bi bi-geo-alt-fill me-2"></i> Pick Up Addresses
                        </a>
                        
                        {{-- Your Orders (Placeholder) --}}
                        <a href="{{ route('seller.settings.tab', ['tab' => 'orders']) }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
                            <i class="bi bi-box-seam-fill me-2"></i> Your Orders
                        </a>
                        {{-- User Page (Active) --}}
                        <a href="{{ route('seller.settings.tab', ['tab' => 'user-page']) }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'user-page' ? 'active shadow-sm' : '' }}" 
                          style="{{ $activeTab == 'user-page' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}"
                          aria-current="{{ $activeTab == 'user-page' ? 'true' : 'false' }}">
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
                
                @if ($activeTab == 'store-info')
                    {{-- Store Information Content (Omitted for brevity) --}}
                    @include('seller.settings.store-info')
                
                @elseif ($activeTab == 'pickup-addresses')
                    {{-- ---------------------------------------- --}}
                    {{-- NEW CONTENT: PICK UP ADDRESSES --}}
                    {{-- ---------------------------------------- --}}
                    <h3 class="fw-bold mb-4">Pick Up Addresses</h3>

                    {{-- Add New Address Button --}}
                    <div class="d-grid mb-4">
                        <button class="btn btn-lg fw-bold" style="background-color: #e5d8c6; border-color: #d8c8b4; color: #5c4a3e;">
                            <i class="bi bi-plus-circle me-2"></i> Add New Pick Up Address
                        </button>
                    </div>

                    {{-- Example Address Card 1 (Placeholder data) --}}
                    <div class="card mb-3 border-0 shadow-sm" style="background-color: #e5d8c6;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Main Warehouse (Primary)</h5>
                            <p class="card-text text-muted mb-1">Jl. Sudirman No. 12, Kav. 5</p>
                            <p class="card-text text-muted mb-3">Jakarta Pusat, DKI Jakarta, 10220</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                            </div>
                        </div>
                    </div>

                    {{-- Example Address Card 2 (Placeholder data) --}}
                    <div class="card mb-3 border-0 shadow-sm" style="background-color: #e5d8c6;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Store Branch B</h5>
                            <p class="card-text text-muted mb-1">Ruko Emerald No. 88, Blok A</p>
                            <p class="card-text text-muted mb-3">Bandung, Jawa Barat, 40111</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                            </div>
                        </div>
                    </div>


                @elseif ($activeTab == 'user-page')
                    {{-- User Page Content (Omitted for brevity) --}}
                    @include('seller.settings.user-page')
                
                @else
                    {{-- Placeholder for Orders tab or an invalid tab --}}
                    <div class="alert alert-warning">
                        Content for the '{{ $activeTab }}' tab is not yet implemented.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection