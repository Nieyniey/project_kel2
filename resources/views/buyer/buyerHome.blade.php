@extends('layouts.app')

@section('title', 'WTS')

@section('content')
<style>
    body {
            background-color: #FFFBE8; 
        }

    .wts-header {
        background-color: #FFFBE8; 
        border-bottom: 1px solid #FFFBE8;
    }
        
    .category-flex-wrapper {
        display: flex;
        justify-content: center; 
        align-items: flex-start; 
    }

    .category-scroll-container {
        overflow-x: auto;
        padding-bottom: 10px; 
        -webkit-overflow-scrolling: touch;
    }
    .category-item {
        flex-shrink: 0; 
        text-align: center;
        margin-right: 15px;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        background-color: #FFFBE8; 
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
    }

    .product-card {
        background-color: #FFFBE8; 
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .product-image-container {
        background-color: #D9D9D9; 
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px; 
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .product-image-shadow:hover {
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); 
    }

    .product-action-icon {
        color: #6C2207; 
    }

    .product-action-circle {
        background-color: #6C2207; 
        color: #FFFBE8 !important; 
        width: 32px;
        height: 32px;
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: none;
        transition: background-color 0.2s;
    }

    .product-action-circle.active {
        background-color: #6C2207;
        color: #F3D643 !important;
    }

    .product-action-circle:hover {
        background-color: #5c4a3e; 
    }
</style>

<div class="container-fluid p-0">
    {{-- HEADER REVISION (FIXED SPACING AND COLOR) --}}
    <div class="wts-header p-3 sticky-top">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Settings (Gear) -> Buyer Settings --}}
                <a href="{{ route('buyer.settings') }}" style="color: #6C2207;">
                    <i class="bi bi-gear-fill" style="font-size: 1.5rem;"></i>
                </a>

            {{-- Logo (Central Element) --}}
            <a href="{{ route('home') }}" class="wts-logo mx-auto">
                <img src="{{ asset('Logo.jpg') }}" alt="WTS Logo" style="height: 50px; width: auto; object-fit: contain;">
            </a>

            {{-- Icons Container (Right-aligned Group for Action Icons) --}}
            <div class="d-flex gap-3 align-items-center position-absolute end-0 me-3"> 
                
                {{-- Search Icon Button --}}
                <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search" style="font-size: 1.5rem; color: #6C2207;"></i>
                </button>
                
                {{-- Chat -> Buyer Chat Index --}}
                <a href="{{ route('chat.index') }}" style="color: #6C2207;">
                    <i class="bi bi-chat-fill" style="font-size: 1.5rem;"></i>
                </a>

                {{-- Wishlist (Heart) -> Buyer Favorites --}}
                <a href="{{ route('buyer.favorites') }}" style="color: #6C2207;">
                    <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
                </a>

                {{-- Cart (Bag) -> Buyer Cart --}}
                <a href="{{ route('buyer.cart') }}" style="color: #6C2207;">
                    <i class="bi bi-bag-fill" style="font-size: 1.5rem;"></i>
                </a>
            </div>
        </div>
    </div>
    {{-- END HEADER REVISION --}}

    {{-- CATEGORIES (Constraint 1: Placeholder, no filtering yet) --}}
    <div class="py-3 bg-white shadow-sm">
        <div class="category-scroll-container px-3" >
            {{-- NEW WRAPPER FOR CENTERING --}}
            <div class="category-flex-wrapper">
                
                {{-- Example Categories (Using placeholders from the image design) --}}
                @php
                    $categories = [
                        ['name' => 'Topi', 'icon' => 'bi-sunglasses'],
                        ['name' => 'Bag', 'icon' => 'bi-handbag-fill'],
                        ['name' => 'Camera', 'icon' => 'bi-camera-fill'],
                        ['name' => 'Sepatu', 'icon' => 'bi-shoe-fill'],
                        ['name' => 'Aksesoris', 'icon' => 'bi-gem'],
                        ['name' => 'Buku', 'icon' => 'bi-book-fill'],
                        ['name' => 'Pakaian', 'icon' => 'bi-smartwatch'],
                        ['name' => 'Lainnya', 'icon' => 'bi-three-dots'],
                    ];
                @endphp

                @foreach ($categories as $category)
                    <div class="category-item">
                        <div class="category-icon">
                            <i class="bi {{ $category['icon'] }}" style="font-size: 1.2rem; color: #f79471;"></i>
                        </div>
                        <small class="text-muted">{{ $category['name'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- END CATEGORIES --}}

    {{-- BLACK FRIDAY SALE BANNER (Placeholder, ignoring miscellaneous pictures) --}}
    <div class="p-4" style="background-color: #E8E0BB;">
        <h3 class="text-white fw-bold" style="color: #6C2207;">BLACK FRIDAY SALE!</h3>
        <p class="text-light" style="color: #6C2207;">Get up to 70% off on selected items.</p>
        <div class="row g-3">
            {{-- Sale Product Placeholder 1 --}}
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <div class="product-image-container p-2">
                                            </div>
                    <div class="p-2">
                        <small class="text-muted">Blazer Prabowo</small><br>
                        <strong style="color: #f79471;">Rp 600.000</strong>
                    </div>
                </div>
            </div>
            {{-- Sale Product Placeholder 2 --}}
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <div class="product-image-container p-2">
                                            </div>
                    <div class="p-2">
                        <small class="text-muted">Celana Jokowi</small><br>
                        <strong style="color: #f79471;">Rp 1.500.000</strong>
                    </div>
                </div>
            </div>
            {{-- Sale Product Placeholder 3 --}}
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <div class="product-image-container p-2">
                                            </div>
                    <div class="p-2">
                        <small class="text-muted">Tas Syahroni</small><br>
                        <strong style="color: #f79471;">Rp 1.000.000</strong>
                    </div>
                </div>
            </div>
            {{-- Sale Product Placeholder 4 --}}
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <div class="product-image-container p-2">
                                            </div>
                    <div class="p-2">
                        <small class="text-muted">Topi Bohel</small><br>
                        <strong style="color: #f79471;">Rp 500.000</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END BLACK FRIDAY SALE --}}

    {{-- RECOMMENDED PRODUCTS (Constraint 5: Iterate through all products) --}}
    <div class="container py-5">
        <h3 class="fw-bold mb-4" style="color: #6C2207;">Recommended Products</h3>
        <div class="row g-4">
            @forelse ($products as $product)
            
            {{-- REV 6: Grid change for 4 products per row --}}
            <div class="col-6 col-md-3 col-xl-3"> 
                
                {{-- REV 1: Removed 'shadow-sm' class from here --}}
                <div class="product-card">
                    
                    {{-- REV 3: Add new class for hover animation/shadow --}}
                    <div class="product-image-container product-image-shadow">
                        <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none d-block w-100 h-100 d-flex align-items-center justify-content-center">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="img-fluid" 
                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            @endif
                        </a>
                    </div>

                    <div class="p-2 text-center">
                        <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                            <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                            <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                        
                        {{-- Product Actions: Centered and using custom circular class --}}
                        <div class="d-flex justify-content-center gap-3 mt-2">

                            @php
                                $user = Auth::user(); 
                                
                                $is_in_cart = $user->inCart($product->product_id);
                                $is_in_wishlist = $user->inWishlist($product->product_id);
                            @endphp
    
                            {{-- Add to Cart (Bag) - NO FORM, just a button --}}
                            <button type="button" 
                                class="product-action-circle add-to-cart-btn {{ $is_in_cart ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('cart.add-ajax') }}" {{-- Use a new AJAX route --}}
                                title="Add to Cart">
                                <i class="bi bi-bag-fill" style="font-size: 1.1rem;"></i> 
                            </button>

                            {{-- Add to Wishlist (Heart) - NO FORM, just a button --}}
                            <button type="button" 
                                class="product-action-circle add-to-wishlist-btn {{ $is_in_wishlist ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('wishlist.add-ajax') }}" {{-- Use a new AJAX route --}}
                                title="Add to Wishlist">
                                <i class="bi bi-heart-fill" style="font-size: 1.1rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">No products found at the moment.</div>
            </div>
            @endforelse
        </div>
    </div>
    {{-- END RECOMMENDED PRODUCTS --}}

</div>
{{-- Search Modal --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background-color: #f8f8f8;">
            <div class="modal-header wts-header border-0 pb-0">
                <div class="d-flex align-items-center w-100">
                    {{-- Back button to close modal --}}
                    <button type="button" class="btn-close fs-4 me-3" data-bs-dismiss="modal" aria-label="Close" style="color: #5c4a3e;"></button>

                    {{-- Search Input inside Modal --}}
                    <input type="search" 
                           id="search-input" 
                           class="form-control rounded-pill p-2" 
                           placeholder="Search product..." 
                           autofocus
                           style="background-color: #ffffff; border: 1px solid #d8c8b4;">
                </div>
            </div>

            <div class="modal-body pt-0">
                <h4 class="fw-bold my-4" style="color: #5c4a3e;">Search Results</h4>
                
                {{-- Dynamic Search Results will be loaded here --}}
                <div class="row g-4" id="search-results-container">
                    {{-- Results will appear here --}}
                </div>

                {{-- Message if no results are found --}}
                <div id="no-results-message" class="alert alert-warning d-none" role="alert">
                    No products found matching your search term.
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Search Modal --}}

@push('scripts')
<script>
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Function to perform the product search via AJAX
    const fetchSearchResults = debounce(function (query) {
        const resultsContainer = $('#search-results-container');
        const noResultsMessage = $('#no-results-message');

        // Clear previous results and hide message
        resultsContainer.empty();
        noResultsMessage.addClass('d-none');

        if (query.length < 3) {
            // Optional: require at least 3 characters
            return;
        }

        // Show a loading spinner if necessary (omitted for brevity)
        
        $.ajax({
            url: '{{ route('products.search.ajax') }}',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                if (response.length > 0) {
                    let html = '';
                    response.forEach(function(product) {
                        // Replicate the product card structure from the recommended section
                        html += `
                        <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                            <div class="product-card shadow-sm">
                                <a href="/products/${product.product_id}" class="text-dark text-decoration-none">
                                    <div class="product-image-container">
                                        ${product.image_path 
                                            ? `<img src="{{ asset('storage') }}/${product.image_path}" alt="${product.name}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">`
                                            : `<i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>`}
                                    </div>
                                </a>
                                <div class="p-2">
                                    <a href="/products/${product.product_id}" class="text-dark text-decoration-none">
                                        <p class="mb-1 fw-bold text-truncate">${product.name}</p>
                                        <p class="mb-2" style="color: #f79471;">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                                    </a>
                                    {{-- Product Actions (simplified for client-side rendering) --}}
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="${product.product_id}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm p-0 bg-transparent border-0" title="Add to Cart">
                                                <i class="bi bi-bag-plus product-action-icon" style="font-size: 1.2rem;"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('wishlist.add') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="${product.product_id}">
                                            <button type="submit" class="btn btn-sm p-0 bg-transparent border-0" title="Add to Wishlist">
                                                <i class="bi bi-heart product-action-icon" style="font-size: 1.2rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    resultsContainer.html(html);
                } else {
                    noResultsMessage.removeClass('d-none');
                }
            },
            error: function(xhr) {
                console.error("Search failed:", xhr.responseText);
            }
        });
    }, 300); // 300ms debounce time

    // Attach event listener to the search input field
    $('#search-input').on('keyup', function() {
        const query = $(this).val();
        fetchSearchResults(query);
    });

    // Optional: Focus the input when the modal opens
    $('#searchModal').on('shown.bs.modal', function () {
        $('#search-input').focus();
    });

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Helper function for sending AJAX request
    function sendProductAction(button, url, productId) {
        // Get CSRF token from meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Success! Toggle the color class based on the new state
                    if (response.is_active) {
                        button.addClass('active');
                        // Optional: Show a small success message like "Added to cart"
                    } else {
                        button.removeClass('active');
                        // Optional: Show a small success message like "Removed from cart"
                    }
                } else {
                    // Handle server-side errors (e.g., product not found)
                    alert('Action failed: ' + response.message);
                }
            },
            error: function(xhr) {
                // Handle network errors or 401/403 errors
                if (xhr.status === 401) {
                    alert('Please log in to use this feature.');
                } else {
                    alert('An unknown error occurred.');
                }
            }
        });
    }

    // Event handler for Add to Cart button
    $('.add-to-cart-btn').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('action-url');
        sendProductAction(button, url, productId);
    });

    // Event handler for Add to Wishlist button
    $('.add-to-wishlist-btn').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('action-url');
        sendProductAction(button, url, productId);
    });
});
</script>
@endpush
@endsection