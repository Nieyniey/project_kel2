@extends('layouts.main')

@section('content')
<div class="container py-4">

    <a href="{{ route('seller.products') }}" class="text-decoration-none d-inline-block mb-3">
        ← Add New Product
    </a>

    <div class="card p-4 shadow-sm" style="background:#f5f0e4; border-radius:16px;">

        <h4 class="fw-bold mb-4">Add New Product</h4>

        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <!-- LEFT -->
                <div class="col-md-6">
                    <label class="fw-semibold">Product Name</label>
                    <input type="text" class="form-control mb-3" name="name">

                    <label class="fw-semibold">Product Description</label>
                    <textarea name="description" class="form-control" rows="5"></textarea>
                </div>

                <!-- RIGHT -->
                <div class="col-md-6">
                    <label class="fw-semibold">Images</label>

                    <div class="text-center p-4 mb-3"
                         style="border:2px dashed #d8b88a; border-radius:12px; background:white;">
                        <span style="color:#7c7c7c;">Click to upload or drag & drop</span>
                        <input type="file" name="images[]" class="form-control mt-3" multiple>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="flex-fill" style="border:1px solid #ddd; border-radius:12px; height:120px; background:white;"></div>
                        <div class="flex-fill" style="border:1px solid #ddd; border-radius:12px; height:120px; background:white;"></div>
                    </div>
                </div>

            </div>

            <!-- DETAILS / PRICING -->
            <div class="row g-4 mt-3">

                <!-- DETAILS -->
                <div class="col-md-6">
                    <div class="p-3" style="background:white; border-radius:10px; border:1px solid #ddd;">
                        <h6 class="fw-bold mb-3">Details</h6>

                        <label class="fw-semibold">Category</label>
                        <input type="text" name="category" class="form-control mb-3">

                        <label class="fw-semibold">Weight</label>
                        <div class="input-group mb-3">
                            <input type="number" name="weight" class="form-control">
                            <span class="input-group-text">kg</span>
                        </div>

                        <label class="fw-semibold">Size (packing)</label>
                        <div class="row g-2">
                            <div class="col"><input type="number" name="length" class="form-control" placeholder="Length"></div>
                            <div class="col"><input type="number" name="breadth" class="form-control" placeholder="Breadth"></div>
                            <div class="col"><input type="number" name="width" class="form-control" placeholder="Width"></div>
                        </div>
                    </div>
                </div>

                <!-- PRICING -->
                <div class="col-md-6">
                    <div class="p-3" style="background:white; border-radius:10px; border:1px solid #ddd;">
                        <h6 class="fw-bold mb-3">Pricing</h6>

                        <label class="fw-semibold">Price</label>
                        <input type="number" name="price" class="form-control mb-3">

                        <label class="fw-semibold">Price Type</label>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-outline-secondary">Fixed Price</button>
                            <button type="button" class="btn btn-warning">Negotiation</button>
                        </div>

                    </div>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-warning px-4">Add Product</button>
            </div>

        </form>
    </div>

</div>
@endsection
