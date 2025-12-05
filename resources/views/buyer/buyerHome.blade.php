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

    {{-- CATEGORIES (Constraint 1: Placeholder, no filtering yet) --}}
    {{-- REVISION 1: Changed background color to match the main content body (#E8E0BB) --}}
    <div class="py-3" style="background-color: #FFFBE8; box-shadow: none;"> 
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

    {{-- BLACK FRIDAY SALE BANNER --}}
    <div class="p-4" style="background-color: #E8E0BB;">
        <h3 class="fw-bold" style="color: #6C2207;">BLACK FRIDAY SALE!</h3>
        <p style="color: #6C2207;">Get up to 70% off on selected items.</p>
        
        @php
            // Placeholder Sale Products (Used for demonstration/looping capability)
            $saleProducts = [
                (object)['product_id' => 101, 'name' => 'Blazer Prabowo', 'price' => 600000, 'image_path' => null],
                (object)['product_id' => 102, 'name' => 'Celana Jokowi', 'price' => 1500000, 'image_path' => null],
                (object)['product_id' => 103, 'name' => 'Tas Syahroni', 'price' => 1000000, 'image_path' => null],
                (object)['product_id' => 104, 'name' => 'Topi Bohel', 'price' => 500000, 'image_path' => null],
                (object)['product_id' => 105, 'name' => 'Extra Item 5', 'price' => 200000, 'image_path' => null],
                (object)['product_id' => 106, 'name' => 'Extra Item 6', 'price' => 300000, 'image_path' => null],
            ];
            $user = Auth::user() ?? (object)['inCart' => fn() => false, 'inWishlist' => fn() => false];
        @endphp

        <div class="horizontal-scroll-wrapper">
            <div class="d-flex flex-nowrap align-items-stretch g-3">
                @foreach ($saleProducts as $product)
                    <div class="scrollable-product-item px-2"> 
                        <div class="product-card" style="background-color: #E8E0BB !important;">
                            <div class="product-image-container product-image-shadow">
                                <a href="#" class="text-dark text-decoration-none d-block w-100 h-100 d-flex align-items-center justify-content-center">
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
                                <a href="#" class="text-dark text-decoration-none">
                                    <p class="mb-1 fw-bold text-truncate" style="color: #6C2207;">{{ $product->name }}</p>
                                    <p class="mb-2" style="color: #6C2207;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </a>
                                
                                {{-- Product Actions: Cart/Wishlist Buttons --}}
                                <div class="d-flex justify-content-center gap-3 mt-2">
                                    @php
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
    {{-- END BLACK FRIDAY SALE --}}

    {{-- RECOMMENDED PRODUCTS --}}
    <div class="container py-5">
        <h3 class="fw-bold mb-4" style="color: #6C2207;">Recommended Products</h3>
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
                <div class="alert alert-info">No products found at the moment.</div>
            </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-5" style="color: #6C2207">
            {{ $products->links('pagination::bootstrap-4') }}
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
    // 1. **NEW:** Store the static Blade output into a JS variable here.
    const STORAGE_BASE_URL = '{{ asset('storage') }}';

    function debounce(func, timeout = 300) {
        // ... (debounce function remains the same) ...
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    const fetchSearchResults = debounce(function (query) {
        const resultsContainer = $('#search-results-container');
        const noResultsMessage = $('#no-results-message');

        resultsContainer.empty();
        noResultsMessage.addClass('d-none');
        
        // Only send request if query is not empty
        if (query.length < 2) return; // Optional: Only search if 2 or more characters are typed

        $.ajax({
            url: '{{ route('products.search.ajax') }}',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                if (response.length > 0) {
                    let html = '';
                    response.forEach(function(product) {
                        
                        // 2. **FIX:** Ensure the path is correct by trimming leading/trailing slashes 
                        //    and explicitly concatenating with the stored base URL.
                        const imagePath = product.image_path ? product.image_path.replace(/^\/+|\/+$/g, '') : '';
                        const imageUrl = imagePath ? `${STORAGE_BASE_URL}/${imagePath}` : '';
                        
                        html += `
                        <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                            <div class="product-card shadow-sm">
                                <a href="/products/${product.product_id}" class="text-dark text-decoration-none">
                                    <div class="product-image-container">
                                        ${product.image_path 
                                            // **FIXED LINE:** Use the clean imageUrl variable
                                            ? `<img src="${imageUrl}" alt="${product.name}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">`
                                            : `<i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>`}
                                    </div>
                                </a>
                                <div class="p-2">
                                    <a href="/products/${product.product_id}" class="text-dark text-decoration-none">
                                        <p class="mb-1 fw-bold text-truncate">${product.name}</p>
                                        <p class="mb-2" style="color: #f79471;">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                                    </a>
                                    {{-- NOTE: For AJAX results, you should use the AJAX action routes (cart.add-ajax) 
                                        and replicate the JS handler (sendProductAction) or rebuild the server-side forms. 
                                        I'll keep the server-side forms for now but this will cause full page reloads. --}}
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

    // ... (rest of the script remains the same) ...
    // Attach event listener to the search input field
    $('#search-input').on('keyup', function() {
        const query = $(this).val().trim();
        fetchSearchResults(query);
    });

    $('#searchModal').on('shown.bs.modal', function () {
        $('#search-input').focus();
    });

    // ... (document.ready function for add-to-cart/wishlist remains the same) ...
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function sendProductAction(button, url, productId) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
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