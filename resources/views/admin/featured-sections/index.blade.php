@extends('admin.layouts.app')

@section('title', 'Add Carousels')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Carousels</h4>
        <a href="{{ route('admin.featured-sections.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Add Carousel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Heading</th>
                        <th>Placement</th>
                        <th>Properties</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $index => $section)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $section->title }}</td>
                            <td>{{ Str::limit($section->heading, 40) ?: '—' }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ $section->heading_placement }}</span></td>
                            <td>{{ $section->properties_count }}</td>
                            <td>{{ $section->sort_order }}</td>
                            <td>
                                @if($section->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.featured-sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.featured-sections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this carousel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No carousels yet. <a href="{{ route('admin.featured-sections.create') }}">Add one</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
