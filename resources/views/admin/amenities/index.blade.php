<!-- resources/views/admin/amenities/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Amenities')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Amenities</h4>
        <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary">Add Amenity</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($amenities as $index => $amenity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $amenity->name }}</td>
                    <td>
                        <a href="{{ route('admin.amenities.edit', $amenity->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this amenity?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
