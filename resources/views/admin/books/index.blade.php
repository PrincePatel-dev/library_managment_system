@extends('layouts.app')

@section('title', 'Manage Books')
@section('page-title', 'Manage Books')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Books</h5>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Book
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>ISBN</th>
                            <th>Qty</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $index => $book)
                            <tr>
                                <td>{{ $books->firstItem() + $index }}</td>
                                <td><strong>{{ $book->title }}</strong></td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $book->category }}</span>
                                </td>
                                <td><small>{{ $book->isbn }}</small></td>
                                <td>{{ $book->quantity }}</td>
                                <td>
                                    @if($book->available_quantity > 0)
                                        <span class="badge bg-success">{{ $book->available_quantity }}</span>
                                    @else
                                        <span class="badge bg-danger">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.books.edit', $book->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No books found. <a href="{{ route('admin.books.create') }}">Add the first book!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($books->hasPages())
            <div class="card-footer">
                {{ $books->links() }}
            </div>
        @endif
    </div>

@endsection
