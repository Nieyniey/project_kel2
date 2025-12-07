@extends('layouts.app')

@section('title', 'Edit Product')

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

    /* Save/Update Button Hover Effect */
    .btn-update-product {
        background-color: #FC5801 !important; 
        color: white !important;
        border-radius: 8px;
        font-weight: 500; 
        transition: background-color 0.2s;
    }
    .btn-update-product:hover {
        background-color: rgba(252, 88, 1, 0.8) !important; 
    }

    /* Delete Button Styling */
    .btn-delete-product {
        background-color: #F3D643 !important; 
        color: #6C2207; !important;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    .btn-delete-product:hover {
        background-color: #c82333 !important; 
        color: white !important;
    }
</style>

{{-- Header --}}
<div class="header-fixed">
    <div class="container"> 
        <div class="d-flex align-items-center">
            <a href="{{ route('seller.products') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #FC5801!important;">
                Edit Produk
            </h5>
        </div>
    </div>
</div>

{{-- Main Content Area --}}
<div class="container py-4"> 
    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" id="product-form">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="fw-semibold form-label">Nama Produk</label>
                    <input type="text" class="form-control custom-form-control" name="name" 
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="fw-semibold form-label">Deskripsi Produk</label>
                    <textarea name="description" class="form-control custom-form-control" rows="6" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- RIGHT COLUMN: Product Image --}}
            <div class="col-md-6 d-flex flex-column">
                
                <label class="fw-semibold form-label">Foto Produk (Sekarang & Baru)</label>

                {{-- Image Upload Area --}}
                <div class="text-center p-4 flex-grow-1 d-flex flex-column justify-content-center align-items-center" 
                    style="border:2px dashed #FC5801; border-radius:12px; background:white;">
                    
                    @if($product->image_path)
                        <img id="current-image-preview" src="{{ asset('storage/' . $product->image_path) }}" alt="Current Product Image" class="img-fluid mb-3" style="max-height: 150px; border-radius: 8px;">
                    @else
                        <i id="current-image-preview-icon" class="bi bi-image-fill mb-3" style="font-size: 2rem; color:#FC5801;"></i>
                    @endif
                    
                    <span style="color:#7c7c7c; display:block; margin-top:5px;">Unggah foto baru (opsional)</span>
                    
                    <input type="file" name="image" class="form-control mt-3 custom-form-control" accept="image/*">
                    @error('image')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">

            <div class="col-md-6">
                <div class="p-4" style="background:white; border-radius:10px; border:1px solid #d8c8b4;">
                    <h6 class="fw-bold mb-3">Detail Produk</h6>

                    <div class="mb-3">
                        <label class="fw-semibold form-label">Kategori</label>
                        {{-- Select dropdown for Category --}}
                        <select name="category_id" class="form-control custom-form-control" required>
                            <option value="" disabled>Pilih Kategori</option>
                            
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}"
                                    {{-- Pre-select the current category --}}
                                    {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                            
                        </select>
                        @error('category_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-1">
                        <label class="fw-semibold form-label">Stock</label>
                        <input type="number" name="stock" class="form-control custom-form-control" 
                               value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-4" style="background:white; border-radius:10px; border:1px solid #d8c8b4;">
                    <h6 class="fw-bold mb-3">Harga</h6>

                    {{-- Original Price / Price Field --}}
                    <div class="mb-3">
                        <label class="fw-semibold form-label" id="price-label">Harga (Rp)</label>
                        <input type="number" id="original-price" class="form-control custom-form-control" 
                               value="{{ old('price', $product->is_sale == 0 ? $product->price : $product->original_price) }}" required>
                        @error('price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Sale Toggle --}}
                    <label class="fw-semibold form-label">Apakah sedang diskon?</label>
                    <div class="d-flex gap-3" id="sale-toggle-group">
                        <button type="button" class="btn {{ $product->is_sale == 0 ? 'btn-toggle-active' : 'btn-toggle-inactive' }}" data-value="no">No</button>
                        <button type="button" class="btn {{ $product->is_sale == 1 ? 'btn-toggle-active' : 'btn-toggle-inactive' }}" data-value="yes">Yes</button>
                    </div>
                    
                    {{-- Hidden field to track sale status (1 or 0 for DB) --}}
                    <input type="hidden" name="is_sale" id="on-sale-status" value="{{ old('is_sale', $product->is_sale) }}">

                    {{-- Sale Price Field (Only appears if sale is toggled) --}}
                    <div id="sale-price-field" class="mt-3 {{ $product->is_sale == 0 ? 'd-none' : '' }}">
                        <label class="fw-semibold form-label">Harga Diskon (Rp)</label>
                        <input type="number" id="sale-price" class="form-control custom-form-control" 
                               value="{{ old('sale_price', $product->is_sale == 1 ? $product->price : null) }}">
                        @error('sale_price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Save and Delete Buttons --}}
        <div class="d-flex justify-content-end gap-3 mt-5">
            
            {{-- Delete Button (Uses a separate form for DELETE request) --}}
            <button type="button" class="btn px-5 py-2 btn-delete-product" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                <i class="bi bi-trash-fill me-1"></i> Hapus Produk
            </button>

            {{-- Save Changes Button (Submits the main update form) --}}
            <button type="submit" class="btn px-5 py-2 btn-update-product">
                Simpan
            </button>
        </div>

    </form>
</div>

{{-- Confirmation Modal for Deletion --}}
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel" style="color: #6C2207;">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: #6C2207;">
                Apakah Anda yakin ingin menghapus {{ $product->name }} secara permanen?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                
                {{-- Separate Form for DELETE Action --}}
                <form id="delete-form" action="{{ route('seller.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete-product">
                        Yes
                    </button>
                </form>
            </div>
        </div>
    </div>
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

        // Function to synchronize the two price inputs based on sale status
        function updateToggleState(value) {
            const isSale = value === 'yes';
            
            // 1. Update Hidden Input for DB
            onSaleStatus.val(isSale ? 1 : 0); 
            
            // 2. Update button styles
            saleToggleGroup.find('.btn').removeClass('btn-toggle-active').addClass('btn-toggle-inactive');
            saleToggleGroup.find(`[data-value="${value}"]`).removeClass('btn-toggle-inactive').addClass('btn-toggle-active');

            if (isSale) {
                // If on sale: Show sale field, set sale price to be the 'price' field in DB
                salePriceField.removeClass('d-none');
                salePriceInput.attr('name', 'price'); 
                originalPriceInput.attr('name', 'original_price'); // This assumes you have an 'original_price' field in your Product model/DB schema.
                priceLabel.text('Original Price (Rp)');
            } else {
                // If NOT on sale: Hide sale field, set original price to be the 'price' field in DB
                salePriceField.addClass('d-none');
                salePriceInput.removeAttr('name'); 
                originalPriceInput.attr('name', 'price'); 
                priceLabel.text('Price (Rp)');
            }
        }

        // Sale/No Sale toggle logic
        saleToggleGroup.on('click', '.btn', function(e) {
            e.preventDefault(); 
            const value = $(this).data('value');
            updateToggleState(value);
        });
        
        // Initial setup to load the correct state based on PHP data
        const initialSaleStatus = onSaleStatus.val() == 1 ? 'yes' : 'no';
        updateToggleState(initialSaleStatus);
    });
</script>
@endpush
@endsection