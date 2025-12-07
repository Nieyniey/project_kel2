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

.horizontal-scroll-wrapper::-webkit-scrollbar {
    display: none;
}

.horizontal-scroll-wrapper {
    -ms-overflow-style: none;
}

.horizontal-scroll-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    white-space: nowrap;
    padding-bottom: 10px;
}

.scrollable-product-item {
    display: inline-block;
    flex-shrink: 0;
    width: 25%;
}

.pagination .page-item:not(.active) .page-link {
    color: #6C2207;
    border-color: #6C2207;
}

.pagination .page-item.active .page-link {
    background-color: #6C2207;
    border-color: #6C2207;
    color: #FFFBE8;
}

.pagination .page-item:not(.active) .page-link:hover {
    color: #FFFBE8;
    background-color: #7d3319;
    border-color: #7d3319;
}

.pagination .page-item.disabled .page-link {
    color: #a0a0a0;
    border-color: #d1d1d1;
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
                <a href="{{ route('homeIn') }}" class="wts-logo mx-auto">
                    <img src="{{ asset('Logo.jpg') }}" alt="WTS Logo" style="height: 50px; width: auto; object-fit: contain;">
                </a>

                {{-- Icons Container (Right-aligned Group for Action Icons) --}}
                <div class="d-flex gap-3 align-items-center position-absolute end-0 me-3">
                    
                    {{-- Search Icon Button --}}
                    <button type="button" class="btn p-0" id="search-icon-btn">
                        <i class="bi bi-search" style="font-size: 1.5rem; color: #6C2207;"></i>
                    </button>
                    
                    {{-- Chat -> Buyer Chat Index --}}
                    <a href="{{ route('chat.index') }}" style="color: #6C2207;">
                        <i class="bi bi-chat-fill" style="font-size: 1.5rem;"></i>
                    </a>

                    {{-- Wishlist (Heart) -> Buyer Favorites --}}
                    <a href="{{ route('wishlist.index') }}" style="color: #6C2207;">
                        <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
                    </a>

                    {{-- Cart (Bag) -> Buyer Cart --}}
                    <a href="{{ route('cart.index') }}" style="color: #6C2207;">
                        <i class="bi bi-bag-fill" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
            </div>
        </div>
        {{-- END HEADER REVISION --}}

    {{-- SEARCH BAR --}}
    <div id="toggled-search-bar" class="container p-3 {{ request()->has('q') ? '' : 'd-none' }}" style="padding-top: 15px !important;">
        <div class="d-flex align-items-center w-100">
            <button type="button" class="btn-close fs-4 me-3" id="close-search-bar" aria-label="Close" style="color: #5c4a3e;"></button>

            {{-- Search Form --}}
            <form action="{{ route('products.search') }}" method="GET" class="w-100 d-flex">
                <input type="search" 
                    id="search-input" 
                    name="q" 
                    class="form-control rounded-pill p-2" 
                    placeholder="Search product..." 
                    value="{{ request('q') }}" {{-- FIX: Insert the current query here --}}
                    style="background-color: #FFFEF7; border: 1px solid #d8c8b4;">
            </form>
        </div>
    </div>
    
    @unless (request()->has('q'))
        {{-- CATEGORIES (NOW IMPLEMENTING FILTERING) --}}
        <div class="py-3" style="background-color: #FFFBE8; box-shadow: none;"> 
            <div class="category-scroll-container px-3" >
                <div class="category-flex-wrapper">
                    
                    {{-- Category Filter Links --}}

                    {{-- 1. "All Products" Link --}}
                    {{-- This link removes the category query parameter --}}
                    <a href="{{ route('homeIn', array_merge(request()->except('category', 'page'))) }}" class="category-item text-decoration-none text-dark">
                        <div class="category-icon" style="{{ is_null($selectedCategorySlug) ? 'background-color: #f79471 !important;' : '' }}">
                            <i class="bi bi-grid-fill" style="font-size: 1.2rem; color: {{ is_null($selectedCategorySlug) ? 'white' : '#f79471' }};"></i>
                        </div>
                        <small class="{{ is_null($selectedCategorySlug) ? 'fw-bold' : 'text-muted' }}">All</small>
                    </a>

                    {{-- 2. Loop through real categories from the controller --}}
                    @foreach ($categories as $category)
                        @php
                            // Check if the current category is the one selected
                            $isActive = ($selectedCategorySlug == $category->slug);
                            $iconColor = $isActive ? 'white' : '#f79471';
                            $bgColor = $isActive ? '#f79471' : '#FFFBE8';
                        @endphp

                        {{-- Link to filter by this category's slug --}}
                        <a href="{{ route('homeIn', ['category' => $category->slug]) }}" class="category-item text-decoration-none text-dark">
                            <div class="category-icon" style="background-color: {{ $bgColor }};">
                                {{-- Assuming the icon column holds the BI class name (e.g., 'bi-sunglasses') --}}
                                <i class="bi {{ $category->icon }}" style="font-size: 1.2rem; color: {{ $iconColor }};"></i>
                            </div>
                            <small class="{{ $isActive ? 'fw-bold' : 'text-muted' }}">{{ $category->name }}</small>
                        </a>
                    @endforeach
                    
                </div>
            </div>
        </div>

        {{-- SALE BANNER --}}
        @if (!request()->has('q') && $saleProducts->count() > 0)
            <div class="p-4" style="background-color: #E8E0BB;">
                <h3 class="fw-bold" style="color: #6C2207;">PRODUK DISKON!</h3>
                <p style="color: #6C2207;">Dapatkan produk-produk ini saat masa diskonnya.</p>
                <div class="horizontal-scroll-wrapper">
                    <div class="d-flex flex-nowrap align-items-stretch g-3">
                        @foreach ($saleProducts as $product)
                                <div class="scrollable-product-item px-2"> 
                                    <div class="product-card" style="background-color: #E8E0BB !important;">
                                        <div class="product-image-container product-image-shadow">
                                            <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none d-block w-100 h-100 d-flex align-items-center justify-content-center">
                                                @if ($product->image_path)
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                        alt="{{ $product->name }}" 
                                                        class="img-fluid" 
                                                        style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                @else
                                                    {{-- Placeholder for image --}}
                                                    <div style="width: 100%; height: 100%; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>

                                        <div class="p-2 text-center">
                                            <a href="{{ route('products.show', $product->product_id) }}" class="text-dark text-decoration-none">
                                                <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                                                <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            </a>
                                            
                                            {{-- Product Actions: Cart/Wishlist Buttons --}}
                                            <div class="d-flex justify-content-center gap-3 mt-2">
                                                @php
                                                    $user = Auth::user() ?? (object)['inCart' => fn() => false, 'inWishlist' => fn() => false];
                                                    $is_in_cart = $user->inCart($product->product_id); 
                                                    $is_in_wishlist = $user->inWishlist($product->product_id);
                                                @endphp
                                            
                                                <button type="button" 
                                                    class="product-action-circle add-to-cart-btn {{ $is_in_cart ? 'active' : '' }}" 
                                                    data-product-id="{{ $product->product_id }}"
                                                    data-action-url="{{ route('cart.add-ajax') }}" 
                                                    title="Add to Cart">
                                                    <i class="bi bi-bag-fill" style="font-size: 1.1rem;"></i> 
                                                </button>
                                            
                                                <button type="button" 
                                                    class="product-action-circle add-to-wishlist-btn {{ $is_in_wishlist ? 'active' : '' }}" 
                                                    data-product-id="{{ $product->product_id }}"
                                                    data-action-url="{{ route('wishlist.add-ajax') }}" 
                                                    title="Add to Wishlist">
                                                    <i class="bi bi-heart-fill" style="font-size: 1.1rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        @endif
    @endunless

    {{-- RECOMMENDED PRODUCTS --}}
    <div class="container py-5">
        @unless (request()->has('q'))
            <h3 class="fw-bold mb-4" style="color: #6C2207;">Rekomendasi untukmu</h3>
        @else
            {{-- Show a title indicating search results --}}
            <h3 class="fw-bold mb-4" style="color: #6C2207;">Search Results for "{{ request('q') }}"</h3>
        @endunless
        <div class="row g-4">
            @forelse ($products as $product)
            <div class="col-6 col-md-3 col-xl-3"> 
                <div class="product-card">
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
                        
                        {{-- Product Actions: Cart/Wishlist Buttons --}}
                        <div class="d-flex justify-content-center gap-3 mt-2">

                            @php
                                $user = Auth::user(); 
                                if ($user) {
                                    $is_in_cart = $user->inCart($product->product_id);
                                    $is_in_wishlist = $user->inWishlist($product->product_id);
                                } else {
                                    $is_in_cart = false;
                                    $is_in_wishlist = false;
                                }
                            @endphp

                            {{-- Add to Cart (Bag) --}}
                            <button type="button" 
                                class="product-action-circle add-to-cart-btn {{ $is_in_cart ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('cart.add-ajax') }}" 
                                title="Add to Cart">
                                <i class="bi bi-bag-fill" style="font-size: 1.1rem;"></i> 
                            </button>

                            {{-- Add to Wishlist (Heart) --}}
                            <button type="button" 
                                class="product-action-circle add-to-wishlist-btn {{ $is_in_wishlist ? 'active' : '' }}" 
                                data-product-id="{{ $product->product_id }}"
                                data-action-url="{{ route('wishlist.add-ajax') }}" 
                                title="Add to Wishlist">
                                <i class="bi bi-heart-fill" style="font-size: 1.1rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info" style="background-color: #F3D643; color: #6C2207; border: #6C2207">No products found at the moment.</div>
            </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-5" style="color: #6C2207">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
        
    </div>

</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const searchBar = $('#toggled-search-bar');
        const searchInput = $('#search-input');
        const searchIconBtn = $('#search-icon-btn'); 
        
        function showSearchBar() {
            searchBar.removeClass('d-none');
        }

        function hideSearchBar() {
            searchBar.addClass('d-none');
        }

        searchIconBtn.on('click', function(e) {
            e.preventDefault(); 
            if (searchBar.hasClass('d-none')) {
                showSearchBar();
            }
        });

        $('#close-search-bar').on('click', function() {
            // Clear the input and redirect to the home page without the search query
            searchInput.val('');
            window.location.href = "{{ route('homeIn', request()->except('q', 'page')) }}";
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-csrf-token"]').attr('content')
            }
        });
        
        function sendProductAction(button, url, productId) {
            const csrfToken = $('meta[name="csrf-csrf-token"]').attr('content');
            
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.is_active) {
                            button.addClass('active');
                        } else {
                            button.removeClass('active');
                        }
                    } else {
                        alert('Action failed: ' + response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('Please log in to use this feature.');
                    } else {
                        alert('An unknown error occurred.');
                    }
                }
            });
        }

        $('.add-to-cart-btn').on('click', function() {
            const button = $(this);
            const productId = button.data('product-id');
            const url = button.data('action-url');
            sendProductAction(button, url, productId);
        });

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