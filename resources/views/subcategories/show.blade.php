@extends('layouts.app')

@section('title', 'Subcategory Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Subcategory Details</h4>
            <div>
                <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('subcategories.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ $subcategory->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $subcategory->id }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>
                            <span class="badge bg-primary">
                                {{ $subcategory->category->name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Subcategory Name</th>
                        <td>{{ $subcategory->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td><code>{{ $subcategory->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $subcategory->description ?: 'No description provided' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($subcategory->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Products Count</th>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $subcategory->products_count ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $subcategory->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $subcategory->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if(($subcategory->products_count ?? 0) > 0)
        <div class="card mt-3 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Products in this Subcategory</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subcategory->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->new_price, 2) }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card mt-3 shadow-sm bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Parent Category:</strong><br>
                        {{ $subcategory->category->name }}
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            Category Slug: {{ $subcategory->category->slug ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection