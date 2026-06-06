@extends('layouts.app')
@section('title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit Category</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                  
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" class="form-control bg-light"
                            value="{{ $category->slug }}" readonly>
                        <small class="text-muted">Auto-generated from name.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" value="1"
                               class="form-check-input" id="is_active"
                               @checked(old('is_active', $category->is_active))>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button type="submit" class="btn btn-warning">Update Category</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection