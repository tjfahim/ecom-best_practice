@extends('layouts.app')
@section('title', 'All Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Products</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Search Product</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Search by product name..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter by Subcategory</label>
                <select name="subcategory_id" class="form-select">
                    <option value="">All Subcategories</option>
                    @foreach($allSubcategories as $sub)
                        <option value="{{ $sub->id }}"
                            @selected(request('subcategory_id') == $sub->id)>
                            {{ $sub->category->name }} → {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->hasAny(['search', 'subcategory_id']))
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="mb-3 text-muted small">
    Showing <strong>{{ $totalProducts }}</strong> products
    from <strong>{{ $subcategories->count() }}</strong> subcategories
</div>

@forelse($subcategories as $subcategory)
    @if($subcategory->products->count())
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex align-items-center gap-2">
            <span class="badge bg-primary">{{ $subcategory->category->name }}</span>
            <span class="text-muted">→</span>
            <strong>{{ $subcategory->name }}</strong>
            <span class="badge bg-secondary ms-auto">
                {{ $subcategory->products->count() }} products
            </span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($subcategory->products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 border">

                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="card-img-top"
                                 style="height:180px; object-fit:cover;"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center
                                        justify-content-center"
                                 style="height:180px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif

                        <div class="card-body pb-1">
                            <h6 class="card-title mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-1">
                                {{ Str::limit($product->description, 50) }}
                            </p>
                            <div class="mt-1">
                                @if($product->old_price)
                                    <small class="text-decoration-line-through text-muted">
                                        ৳{{ number_format($product->old_price, 2) }}
                                    </small>
                                @endif
                                <span class="text-success fw-bold">
                                    ৳{{ number_format($product->new_price, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 d-flex gap-1 flex-wrap">
                            <a href="{{ route('products.slug', $product->slug) }}"
                               class="btn btn-outline-info btn-sm flex-fill">View</a>
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-outline-warning btn-sm flex-fill">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Del</button>
                            </form>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@empty
    <div class="text-center py-5 text-muted">
        <p class="mb-1">No products found.</p>
        @if(request()->hasAny(['search', 'subcategory_id']))
            <a href="{{ route('products.index') }}">Clear filters</a>
        @else
            <a href="{{ route('products.create') }}">Add your first product →</a>
        @endif
    </div>
@endforelse

@endsection