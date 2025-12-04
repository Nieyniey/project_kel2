@extends('layouts.main')

@section('title', 'WTS')

@section('content')
<style>
    /* Styling to replicate the aesthetic of the provided image (e.g., Image of a stylish e-commerce homepage with fashion products). */
    .wts-header {
        background-color: #f8f8f8; /* Light background for the header area */
        border-bottom: 1px solid #e0e0e0;
    }
    .wts-logo {
        font-family: serif; /* Example font for WTS logo */
        font-weight: bold;
        font-size: 2rem;
        color: #f79471; /* Accent color matching the design */
    }
    .category-scroll-container {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px; /* Space for scroll bar */
        -webkit-overflow-scrolling: touch;
    }
    .category-item {
        display: inline-block;
        min-width: 80px;
        text-align: center;
        margin-right: 15px;
    }
    .category-icon {
        width: 50px;
        height: 50px;
        background-color: #f7e6d1; /* Light brown background */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
    }
    .product-card {
        background-color: #f7e6d1; /* Light brown background for cards */
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .product-image-container {
        background-color: #ffffff; /* White background for the product image area */
        height: 180px; /* Fixed height for image area */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-action-icon {
        color: #5c4a3e; /* Darker accent for icons */
    }
</style>

<div class="container-fluid p-0">
    {{-- HEADER --}}
    <div class="wts-header p-3 sticky-top">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Search Bar (Placeholder) --}}
            <button type="button" class="btn p-0 me-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search" style="font-size: 1.5rem; color: #5c4a3e;"></i>
            </button>

            {{-- Logo --}}
            <span class="wts-logo me-3">WTS</span>

            {{-- Icons --}}
            <div class="d-flex gap-3 align-items-center">
                {{-- Settings (Gear) -> Buyer Settings --}}
                <a href="{{ route('buyer.buyerSettings') }}" class="text-dark">
                    <i class="bi bi-gear-fill" style="font-size: 1.5rem;"></i>
                </a>
                
                {{-- Chat -> Buyer Chat Index --}}
                <a href="{{ route('chat.index') }}" class="text-dark">
                    <i class="bi bi-chat-fill" style="font-size: 1.5rem;"></i>
                </a>

                {{-- Wishlist (Heart) -> Buyer Favorites --}}
                <a href="{{ route('buyer.favorites') }}" class="text-dark">
                    <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
                </a>

                {{-- Cart (Bag) -> Buyer Cart --}}
                <a href="{{ route('buyer.cart') }}" class="text-dark">
                    <i class="bi bi-bag-fill" style="font-size: 1.5rem;"></i>
                </a>
            </div>
        </div>
    </div>
    {{-- END HEADER --}}

    {{-- CATEGORIES (Constraint 1: Placeholder, no filtering yet) --}}
    <div class="py-3 bg-white shadow-sm">
        <div class="category-scroll-container px-3">
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
    {{-- END CATEGORIES --}}

    {{-- BLACK FRIDAY SALE BANNER (Placeholder, ignoring miscellaneous pictures) --}}
    <div class="p-4" style="background-color: #1a1a1a;">
        <h3 class="text-white fw-bold">BLACK FRIDAY SALE!</h3>
        <p class="text-light">Get up to 70% off on selected items.</p>
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
        <h3 class="fw-bold mb-4" style="color: #5c4a3e;">Recommended Products</h3>
        <div class="row g-4">
            @forelse ($products as $product)
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                <div class="product-card shadow-sm">
                    {{-- Product Image / Link to Detail --}}
                    <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                        <div class="product-image-container">
                            {{-- If you had image paths, you'd use them here. Using placeholder for now. --}}
                                                    </div>
                    </a>

                    <div class="p-2">
                        <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                            <p class="mb-1 fw-bold text-truncate">{{ $product->name }}</p>
                            <p class="mb-2" style="color: #f79471;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                        
                        {{-- Product Actions (Constraint 3: Cart and Wishlist only) --}}
                        <div class="d-flex gap-2">
                            {{-- Add to Cart --}}
                            <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm p-0 bg-transparent border-0" title="Add to Cart">
                                    <i class="bi bi-bag-plus product-action-icon" style="font-size: 1.2rem;"></i>
                                </button>
                            </form>

                            {{-- Add to Wishlist --}}
                            <form action="{{ route('wishlist.add') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <button type="submit" class="btn btn-sm p-0 bg-transparent border-0" title="Add to Wishlist">
                                    <i class="bi bi-heart product-action-icon" style="font-size: 1.2rem;"></i>
                                </button>
                            </form>
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
    // Debounce function to limit how often a function is executed
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
                                    [Image of ${product.name}]
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
</script>
@endpush
@endsection