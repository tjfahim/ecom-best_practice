@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit Product</h4>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select id="category_select" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected($product->subcategory->category_id == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subcategory <span class="text-danger">*</span></label>
                            <select name="subcategory_id" id="subcategory_select"
                                    class="form-select @error('subcategory_id') is-invalid @enderror">
                                @foreach($subcategories as $sub)
                                    <option value="{{ $sub->id }}"
                                            data-category="{{ $sub->category_id }}"
                                            @selected(old('subcategory_id', $product->subcategory_id) == $sub->id)>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subcategory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ $product->slug }}" readonly>
                        <small class="text-muted">Auto-generated from name.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4"
                                class="form-control @error('description') is-invalid @enderror"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Old Price</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="old_price" step="0.01" min="0"
                                       class="form-control"
                                       value="{{ old('old_price', $product->old_price) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="new_price" step="0.01" min="0"
                                       class="form-control @error('new_price') is-invalid @enderror"
                                       value="{{ old('new_price', $product->new_price) }}">
                            </div>
                            @error('new_price')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Image</label>
                        @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="img-thumbnail" style="max-height: 120px;"
                                 alt="Current image">
                            <small class="text-muted d-block mt-1">Current image. Upload new to replace.</small>
                        </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="form-control @error('image') is-invalid @enderror"
                               onchange="previewImage(this)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image_preview" src="#" alt="New preview"
                             class="img-thumbnail mt-2 d-none" style="max-height: 120px;">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" value="1"
                               class="form-check-input" id="is_active"
                               @checked(old('is_active', $product->is_active))>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button type="submit" class="btn btn-warning">Update Product</button>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
