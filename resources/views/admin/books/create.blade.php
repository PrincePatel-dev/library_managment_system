@extends('layouts.app')

@section('title', 'Add New Book')
@section('page-title', 'Add New Book')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header fw-semibold">
                    <i class="bi bi-plus-circle"></i> Add New Book
                </div>
                <div class="card-body">

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.books.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Book Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title') }}" placeholder="Enter book title" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control"
                                       value="{{ old('author') }}" placeholder="Enter author name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach(['Science', 'Mathematics', 'Engineering', 'Technology', 'History', 'Literature', 'Computer Science', 'Biology', 'Chemistry', 'Physics', 'Economics', 'Other'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">ISBN Number <span class="text-danger">*</span></label>
                                <input type="text" name="isbn" class="form-control"
                                       value="{{ old('isbn') }}" placeholder="e.g. 978-3-16-148410-0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Total Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control"
                                       value="{{ old('quantity', 1) }}" min="1" required>
                                <small class="text-muted">Available quantity will be set equal to this.</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                          placeholder="Brief description of the book (optional)">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Book
                                </button>
                                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary ms-2">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
