@extends('admin.layouts.app')

@section('title', 'Property List')

@section('content')

<!-- Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h4 class="mb-0">All Properties ({{ $totalProperty }})</h4>
  
</div>

<!-- Filter Bar -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
      <div class="col-md-2">
        <label class="form-label">Property Type</label>
        <select name="propertyType" class="form-select">
          <option value="">All</option>
          @foreach ($propertyTypes as $key => $label)
            <option value="{{ $key }}" {{ request('propertyType') == $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Category</label>
        <select name="property_category_id" class="form-select">
          <option value="">All</option>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('property_category_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Child Type</label>
        <select name="child_type_id" class="form-select">
          <option value="">All</option>
          @foreach ($childTypes as $type)
            <option value="{{ $type->id }}" {{ request('child_type_id') == $type->id ? 'selected' : '' }}>
              {{ $type->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Status</label>
       
        <select name="verified" class="form-select">
          <option value="">All</option>
          <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
          <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Not Verified</option>
      </select>

      </div>

      <div class="col-md-2">
        <label class="form-label">Location</label>
        <select name="location" class="form-select">
          <option value="">All</option>
          @foreach ($locations as $loc)
            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 text-end">
        <button class="btn btn-sm btn-primary w-100"><i class="fa fa-filter me-1"></i> Filter</button>
      </div>
    </form>
  </div>
</div>

<!-- Property Table -->
<div class="card shadow-sm border-0">
  <div class="card-body table-responsive">
  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Property</th>
          <th>Type</th>
          <th>Status</th>
          <th>Price</th>
          <th>Posted By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($properties as $index => $property)
          <tr>
            <td>{{ $properties->firstItem() + $index }}</td>
            <td>
              <div class="d-flex align-items-center">
                
                <div>
                  <!-- <div class="fw-bold">{{ $property->property_name }}</div> -->
                  <div class="fw-bold">{{ $property->propertyName }}</div>
                  <small class="text-muted">{{ $property->address }}</small>
                </div>
              </div>
            </td>
           

            <td>{{ $propertyTypes[$property->propertyType] ?? 'N/A' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.properties.toggleVerified', $property->id) }}">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm {{ $property->verified ? 'btn-success' : 'btn-outline-danger' }}">
                        {{ $property->verified ? 'Verified' : 'Not Verified' }}
                    </button>
                </form>
            </td>


            <td>₹{{ number_format($property->price) }}</td>
            <td>{{ $property->user->name ?? 'N/A' }}</td>
            <td>
            <a href="{{ route('property.show', $property->id) }}" class="btn btn-sm px-2 btn-outline-info" target="_blank">
              <i class="fa fa-eye"></i></a>


              <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm px-2 btn-outline-warning"><i class="fa fa-edit"></i></a>

              <form action="{{ route('admin.properties.duplicate', $property->id) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Duplicate this property?')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-secondary px-2">
                      <i class="fa fa-copy"></i>
                  </button>
              </form>

              
              <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this property?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger px-2">
        <i class="fa fa-trash"></i>
    </button>
</form>



            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No properties found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="row">
                        <div class="col-12">
                            <ul class="pagination mt-3">
                                @if ($properties->onFirstPage())
                                    <li class="page-item disabled me-auto">
                                        <span class="page-link b-radius-none">Prev</span>
                                    </li>
                                @else
                                    <li class="page-item me-auto">
                                        <a class="page-link b-radius-none"
                                            href="{{ $properties->previousPageUrl() }}">Prev</a>
                                    </li>
                                @endif
                                @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $properties->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                    </li>
                                @endforeach
                                @if ($properties->hasMorePages())
                                    <li class="page-item ms-auto">
                                        <a class="page-link b-radius-none"
                                            href="{{ $properties->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled ms-auto">
                                        <span class="page-link b-radius-none">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

@endsection
