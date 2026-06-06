@extends('layouts.app')

@section('title', 'Subcategories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Subcategories</h4>
    <a href="{{ route('subcategories.create') }}" class="btn btn-primary btn-sm">+ Add Subcategory</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('subcategories.index') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="1" @selected(request('status') == '1')>Active</option>
                    <option value="0" @selected(request('status') == '0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Subcategory Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Products</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subcategories as $sub)
                <tr>
                    <td>{{ $subcategories->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $sub->category->name ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $sub->name }}</td>
                    <td><code class="text-muted">{{ $sub->slug }}</code></td>
                    <td>{{ Str::limit($sub->description, 40, '...') ?? '—' }}</td>
                    <td>
                        @if($sub->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $sub->products_count ?? $sub->products->count() }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('subcategories.show', $sub->id) }}" 
                           class="btn btn-info btn-sm text-white">View</a>
                        
                        <a href="{{ route('subcategories.edit', $sub->id) }}"
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('subcategories.destroy', $sub->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this subcategory? All related products will also be deleted!')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No subcategories found.
                        @if(request('search') || request('status'))
                            <br>
                            <a href="{{ route('subcategories.index') }}">Clear filters</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $subcategories->appends(request()->query())->links() }}
</div>

<div class="mt-2 text-muted small">
    Showing {{ $subcategories->firstItem() ?? 0 }} to {{ $subcategories->lastItem() ?? 0 }} 
    of {{ $subcategories->total() }} subcategories
</div>
@endsection