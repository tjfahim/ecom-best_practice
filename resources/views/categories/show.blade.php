@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Category Details</h4>
            <div>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $category->description ?: 'No description' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Subcategories Count</th>
                        <td>
                            <span class="badge bg-info">{{ $category->subcategories_count ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $category->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $category->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($category->subcategories_count > 0)
        <div class="card mt-3 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    Subcategories
                    <span class="badge bg-info ms-2">{{ $category->subcategories_count }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($category->subcategories as $sub)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $sub->name }}
                            <span class="badge {{ $sub->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $sub->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection