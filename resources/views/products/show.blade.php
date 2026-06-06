@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">

        <div class="mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">← Back to Products</a>
        </div>

        <div class="card shadow-sm">
            <div class="row g-0">

                <div class="col-md-5">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="img-fluid rounded-start"
                         style="width: 100%; height: 100%; object-fit: cover; min-height: 300px;"
                         alt="{{ $product->name }}">
                    @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-start"
                         style="min-height: 300px;">
                        <span class="text-muted">No Image</span>
                    </div>
                    @endif
                </div>

                <div class="col-md-7">
                    <div class="card-body p-4">

                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb small">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('categories.index') }}">Categories</a>
                                </li>
                                <li class="breadcrumb-item">
                                    {{ $product->subcategory->category->name }}
                                </li>
                                <li class="breadcrumb-item">
                                    {{ $product->subcategory->name }}
                                </li>
                                <li class="breadcrumb-item active">{{ $product->name }}</li>
                            </ol>
                        </nav>

                        <h3 class="card-title mb-1">{{ $product->name }}</h3>
                        <p class="text-muted small mb-3">
                            Slug: <code>{{ $product->slug }}</code>
                        </p>

                        <div class="mb-3">
                            @if($product->old_price)
                            <span class="text-decoration-line-through text-muted fs-5 me-2">
                                ৳{{ number_format($product->old_price, 2) }}
                            </span>
                            @endif
                            <span class="text-success fw-bold fs-3">
                                ৳{{ number_format($product->new_price, 2) }}
                            </span>
                            @if($product->old_price && $product->old_price > $product->new_price)
                            <span class="badge bg-danger ms-2">
                                @if($product->discount_percentage)
                                    <span class="badge bg-danger ms-2">
                                        {{ $product->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>

                        @if($product->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold">Description</h6>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-warning">Edit Product</a>

                            <form action="{{ route('products.destroy', $product->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection