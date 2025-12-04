@extends('layouts.main') 

@section('title', 'Seller Settings')

@php
    // Determine the active tab based on the current route/logic
    $activeTab = $activeTab ?? 'store-info'; 
@endphp

@section('content')
<div class="container py-5" style="background-color: #f8f8f8;">
    <div class="d-flex align-items-center mb-4">
        {{-- Matches the "← Seller Settings" look --}}
        <a href="{{ route('home') }}" class="text-dark me-3" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left"></i> 
        </a>
        <h2 class="fw-bold mb-0">Seller Settings</h2>
    </div>

    <div class="row g-4">
        {{-- Left Panel: Navigation (Styled with light brown background) --}}
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm border-0" style="background-color: #f7e6d1;">
                <div class="card-body p-4">
                    {{-- Store Info Header --}}
                    <div class="d-flex flex-column align-items-center mb-4">
                        {{-- Profile Picture Placeholder --}}
                        <div class="rounded-circle bg-light border border-secondary d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; overflow: hidden;">
                            {{-- Placeholder for a store logo --}}
                            <img src="{{ asset('placeholder.jpg') }}" 
                                 alt="Store Logo" 
                                 class="w-100 h-100 object-fit-cover rounded-circle">
                        </div>
                        <h5 class="fw-bold mb-0">{{ $seller->store_name ?? 'Your Store' }}</h5>
                        <small class="text-muted">Seller</small>
                    </div>

                    {{-- Navigation Links (Active state styled to match the image) --}}
                    <div class="list-group list-group-flush">
                        {{-- Store Information --}}
                        <a href="{{ route('seller.settings') }}" 
                          class="list-group-item list-group-item-action border-0 {{ $activeTab == 'store-info' ? 'active shadow-sm' : '' }}" 
                          style="{{ $activeTab == 'store-info' ? 'background-color: #f79471; color: white; border-radius: 8px;' : 'background-color: transparent;' }}"
                          aria-current="{{ $activeTab == 'store-info' ? 'true' : 'false' }}">
                            <i class="bi bi-shop me-2"></i> Store Information
                        </a>
                        {{-- Other Pages (Not functional yet, using placeholder links) --}}
                        <a href="{{ route('seller.settings.orders') }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
                            <i class="bi bi-box-seam-fill me-2"></i> Your Orders
                        </a>
                        <a href="{{ route('seller.settings.user-page') }}" class="list-group-item list-group-item-action border-0" style="background-color: transparent;">
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

        {{-- Right Panel: Content (Store Information Form) --}}
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-sm border-0 p-4" style="background-color: #f7e6d1;">
                <h3 class="fw-bold mb-4">Store Information</h3>

                {{-- Feedback Messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form action="{{ route('seller.settings.update.store') }}" method="POST">
                    @csrf

                    {{-- Store Name (The only field we are saving) --}}
                    <div class="mb-4">
                        <label for="store_name" class="form-label fw-bold">Store Name</label>
                        <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                               id="store_name" name="store_name" 
                               value="{{ old('store_name', $seller->store_name ?? '') }}" style="background-color: #e5d8c6; border-color: #d8c8b4;">
                        @error('store_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Placeholder: Store Descriptions (Non-functional based on DB structure) --}}
                    <div class="mb-4">
                        <label for="store_description_ph" class="form-label fw-bold">Store Descriptions</label>
                        <textarea class="form-control" id="store_description_ph" rows="6" disabled style="background-color: #e5d8c6; border-color: #d8c8b4;">(This field is currently not saved to the database. Example text shown in image: ahsfdfkajhfjanhfjanfanf jdfalkfajfa;kfaknfas fjakfjanf;klafajklalkfalkf;fja;lna jankrnfa kmtbkjanf afjfa;fna fjajknfkhajmjaffnaf ajfhaf;a fajfhafnak;fk anfa fja;fkan;lkfbasbtkjoalanmfa fja;fbwuedfjkla bf;kjahnf nfkjdahfaneinfejnfansfndlfndalnfkalfnafanafkfaknfnafklknalfaltanfjeiofneifjdafna sthoiehithheurehmefklsdnffkjejfh eofianf)</textarea>
                    </div>

                    {{-- Placeholder: Instagram @ (Non-functional based on DB structure) --}}
                    <div class="mb-4">
                        <label for="instagram_ph" class="form-label fw-bold">Instagram @</label>
                        <input type="text" class="form-control" id="instagram_ph" value="@prabroroJualGinjai" disabled style="background-color: #e5d8c6; border-color: #d8c8b4;">
                    </div>

                    {{-- Placeholder: Set Store Status (Non-functional based on DB structure) --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Set Store Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="store_status_ph" id="statusActive" value="active" checked disabled>
                                <label class="form-check-label" for="statusActive">
                                    Active <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="store_status_ph" id="statusClosed" value="temporarily_closed" disabled>
                                <label class="form-check-label" for="statusClosed">
                                    Temporarily Closed <i class="bi bi-x-circle-fill text-danger ms-1"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end gap-3 pt-3">
                        <button type="reset" class="btn btn-outline-secondary px-4">Discard Changes</button>
                        {{-- Use the primary color from the image for the Save button --}}
                        <button type="submit" class="btn btn-primary px-4" style="background-color: #f79471; border-color: #f79471;">Save Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection