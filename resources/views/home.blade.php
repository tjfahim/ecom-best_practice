@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                    <i class="bi bi-folder fs-1 text-primary"></i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold">{{ $totalCategories ?? 0 }}</h2>
                    <p class="text-muted mb-0 small text-uppercase">Categories</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 p-3 rounded-3">
                    <i class="bi bi-tags fs-1 text-success"></i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold">{{ $totalSubcategories ?? 0 }}</h2>
                    <p class="text-muted mb-0 small text-uppercase">Subcategories</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                    <i class="bi bi-box-seam fs-1 text-warning"></i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold">{{ $totalProducts ?? 0 }}</h2>
                    <p class="text-muted mb-0 small text-uppercase">Products</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">📁 Categories</h4>
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">
        Manage All →
    </a>
</div>

@if($categories->count())
<div class="row g-3 mb-5">
    @foreach($categories as $category)
    <div class="col-md-4 col-lg-3">
        <div class="card border h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="fs-1">📂</span>
                    <span class="badge bg-secondary">{{ $category->subcategories->count() }} sub</span>
                </div>
                <h6 class="card-title mb-1">{{ $category->name }}</h6>
                <p class="small text-muted mb-0">{{ Str::limit($category->description, 40) }}</p>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('subcategories.index') }}" class="small">View Products →</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-light text-center mb-5">
    <p class="mb-0">No categories yet.</p>
    <a href="{{ route('categories.create') }}" class="small">Create your first category →</a>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">🛍️ Latest Products</h4>
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">
        View All →
    </a>
</div>

@if($latestProducts->count())
<div class="row g-3 mb-5">
    @foreach($latestProducts as $product)
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top" 
                     style="height: 180px; object-fit: cover;"
                     alt="{{ $product->name }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                    <i class="bi bi-image fs-1 text-muted"></i>
                </div>
            @endif
            <div class="card-body">
                <span class="badge bg-info mb-2">{{ $product->subcategory->name ?? 'No Subcategory' }}</span>
                <h6 class="card-title">{{ $product->name }}</h6>
                <p class="card-text small text-muted">{{ Str::limit($product->description, 50) }}</p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        @if($product->old_price)
                            <small class="text-muted text-decoration-line-through me-1">
                                ৳{{ number_format($product->old_price, 2) }}
                            </small>
                        @endif
                        <strong class="text-success">৳{{ number_format($product->new_price, 2) }}</strong>
                    </div>
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-light text-center mb-5">
    <p class="mb-0">No products yet.</p>
    <a href="{{ route('products.create') }}" class="small">Add your first product →</a>
</div>
@endif

<div class="card bg-dark text-white border-0">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-1">Ready to manage your store?</h4>
                <p class="mb-0 text-white-50">Add categories, subcategories and products from the admin panel.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('categories.create') }}" class="btn btn-primary me-2">+ Category</a>
                <a href="{{ route('subcategories.create') }}" class="btn btn-outline-light">+ Subcategory</a>
            </div>
        </div>
    </div>
</div>

@endsection