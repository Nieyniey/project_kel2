@extends('layouts.app') 

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4" style="color: #6C2207;">Search Results for: "{{ $searchQuery }}"</h3>
    
    <div class="row g-4">
        @forelse ($products as $product)
            {{-- Use your existing product card structure here --}}
            <div class="col-6 col-md-3 col-xl-3"> 
                <div class="product-card">
                    {{-- ... Product details and buttons ... --}}
                </div>
            </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No products found matching "{{ $searchQuery }}".</div>
        </div>
        @endforelse
    </div>

    {{-- Add Pagination Links --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection