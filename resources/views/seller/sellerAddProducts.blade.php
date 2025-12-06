@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<style>
    /* Global Background Color (Applied to the whole page) */
    body {
        background-color: #FFFBE8 !important;
        color: #6C2207 !important;
    }

    /* Fixed Header Styling */
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

    /* Custom Form Control Styling (The Bars) */
    .custom-form-control {
        border-radius: 8px;
        border: 1px solid #d8c8b4;
        background-color: #FFFEF7;
        color: #6C2207;
        padding: 0.5rem 0.75rem;
    }
    .custom-form-control:focus {
        border-color: #FC5801;
        box-shadow: 0 0 0 0.25rem rgba(252, 88, 1, 0.25);
        background-color: white;
    }
    
    /* Custom Button Styles for Sale Toggle */
    .btn-toggle-active {
        background-color: #FC5801 !important; 
        color: white !important;
        border-color: #FC5801 !important;
        font-weight: 500; /* Less bold */
    }
    .btn-toggle-inactive {
        background-color: transparent !important; 
        color: #6C2207 !important;
        border-color: #6C2207 !important;
        font-weight: 500; /* Less bold */
    }

    /* Add Product Button Hover Effect (Request #4) */
    .btn-add-product {
        background-color: #FC5801 !important; 
        color: white !important;
        border-radius: 8px;
        font-weight: 500; /* Less bold */
        transition: background-color 0.2s;
    }

    .btn-add-product:hover {
        /* FC5801 with 40% opacity */
        background-color: rgba(252, 88, 1, 0.6) !important; 
    }
</style>

{{-- Header: Back Button and Title (Fixed/Sticky) --}}
<div class="header-fixed">
    <div class="container"> 
        <div class="d-flex align-items-center">
            {{-- Back Arrow (using bi-arrow-left for consistency) --}}
            <a href="{{ route('seller.products') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#6C2207 !important;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h5 class="fw-bold mb-0" style="color: #6C2207;">
                Add New Product
            </h5>
        </div>
    </div>
</div>

{{-- Main Content Area: Centered Container (Request #3: Form uses full page background) --}}
<div class="container py-4"> 
    {{-- Removed the main card container --}}

    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" id="product-form">
        @csrf

        <div class="row g-4">

    {{-- LEFT COLUMN: Name and Description --}}
        <div class="col-md-6">
            <div class="mb-4">
                <label class="fw-semibold form-label">Product Name</label>
                <input type="text" class="form-control custom-form-control" name="name">
            </div>

            <div class="mb-4">
                <label class="fw-semibold form-label">Product Description</label>
                <textarea name="description" class="form-control custom-form-control" rows="6"></textarea>
            </div>
        </div>

        {{-- RIGHT COLUMN: Product Image (Now uses Flexbox for Height Alignment) --}}
        <div class="col-md-6 d-flex flex-column">
            
            <label class="fw-semibold form-label">Product Image</label>

            {{-- Image Upload Area - h-100 or flex-grow-1 forces it to fill the remaining height --}}
            <div class="text-center p-4 h-100" 
                style="border:2px dashed #FC5801; border-radius:12px; background:white;">
                
                <i class="bi bi-image-fill" style="font-size: 2rem; color:#FC5801;"></i>
                <span style="color:#7c7c7c; display:block; margin-top:5px;">Upload 1 Image</span>
                
                {{-- We need to ensure the file input is not pushing the height beyond the parent --}}
                <input type="file" name="image" class="form-control mt-3 custom-form-control" accept="image/*" required>
            </div>
        </div>
    </div>

        <div class="row g-4 mt-3">

            <div class="col-md-6">
                <div class="p-4" style="background:white; border-radius:10px; border:1px solid #d8c8b4;">
                    <h6 class="fw-bold mb-3">Product Details</h6>

                    <div class="mb-3">
                        <label class="fw-semibold form-label">Category</label>
                        {{-- Changed input to select --}}
                        <select name="category_id" class="form-control custom-form-control" required>
                            <option value="" disabled selected>Select a Category</option>
                            
                            {{-- Loop through the categories passed from the controller --}}
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                            
                        </select>
                        
                        {{-- Optional: Display error validation message --}}
                        @error('category_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        
                    </div>

                    <div class="mb-1">
                        <label class="fw-semibold form-label">Stock</label>
                        <input type="number" name="stock" class="form-control custom-form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-4" style="background:white; border-radius:10px; border:1px solid #d8c8b4;">
                    <h6 class="fw-bold mb-3">Pricing</h6>

                    <div class="mb-3">
                        <label class="fw-semibold form-label" id="price-label">Price (Rp)</label>
                        <input type="number" name="price" id="original-price" class="form-control custom-form-control" required>
                    </div>

                    <label class="fw-semibold form-label">Is it on sale?</label>
                    <div class="d-flex gap-3" id="sale-toggle-group">
                        <button type="button" class="btn btn-toggle-active" data-value="no">No</button>
                        <button type="button" class="btn btn-toggle-inactive" data-value="yes">Yes</button>
                    </div>
                    
                    {{-- Hidden field to track sale status --}}
                    <input type="hidden" name="on_sale_status" id="on-sale-status" value="no">

                    {{-- Sale Price Field (Request #2: Only appears if sale is toggled) --}}
                    <div id="sale-price-field" class="mt-3 d-none">
                        <label class="fw-semibold form-label">Sale Price (Rp)</label>
                        <input type="number" id="sale-price" class="form-control custom-form-control">
                    </div>
                </div>
            </div>

        </div>

        {{-- Save Button --}}
        <div class="text-end mt-5">
            <button type="submit" class="btn px-5 py-2 btn-add-product">
                Add Product
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const productForm = $('#product-form');
        const saleToggleGroup = $('#sale-toggle-group');
        const onSaleStatus = $('#on-sale-status');
        const salePriceField = $('#sale-price-field');
        const salePriceInput = $('#sale-price');
        const originalPriceInput = $('#original-price');
        const priceLabel = $('#price-label');

        // Initial toggle setup
        function updateToggleState(value) {
            onSaleStatus.val(value);
            saleToggleGroup.find('.btn').removeClass('btn-toggle-active').addClass('btn-toggle-inactive');
            saleToggleGroup.find(`[data-value="${value}"]`).removeClass('btn-toggle-inactive').addClass('btn-toggle-active');

            if (value === 'yes') {
                salePriceField.removeClass('d-none');
                salePriceInput.attr('name', 'price'); // Give Sale Price the DB column name
                originalPriceInput.removeAttr('name'); // Remove DB column name from Original Price
                priceLabel.text('Original Price (Rp)');
            } else {
                salePriceField.addClass('d-none');
                salePriceInput.removeAttr('name'); // Remove DB column name from Sale Price
                originalPriceInput.attr('name', 'price'); // Give Original Price the DB column name
                priceLabel.text('Price (Rp)');
            }
        }

        // Sale/No Sale toggle logic
        saleToggleGroup.on('click', '.btn', function(e) {
            e.preventDefault(); 
            const value = $(this).data('value');
            updateToggleState(value);
        });
        
        // Initial setup to handle browser back/forward consistency
        updateToggleState('no'); 
    });
</script>
@endpush
@endsection