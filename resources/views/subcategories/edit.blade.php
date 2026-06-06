@extends('layouts.app')
@section('title', 'Edit Subcategory')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit Subcategory</h4>
            <a href="{{ route('subcategories.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $subcategory->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subcategory Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $subcategory->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3"
                                  class="form-control">{{ old('description', $subcategory->description) }}</textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" value="1"
                               class="form-check-input" id="is_active"
                               @checked(old('is_active', $subcategory->is_active))>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button type="submit" class="btn btn-warning">Update Subcategory</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection