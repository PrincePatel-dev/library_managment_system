@extends('layouts.app')

@section('title', 'Browse Books')
@section('page-title', 'Browse Books')

@section('content')

    <div class="mb-3">
        <h5>Browse Library Books</h5>
        <p class="text-muted">Search for books by title, author, or category.</p>
    </div>

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('student.books') }}" method="GET">
                <div class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control form-control-lg"
                           placeholder="Search by book title, author, or category..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('student.books') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Search Result Info -->
    @if(request('search'))
        <p class="text-muted mb-3">
            Showing results for <strong>"{{ request('search') }}"</strong>
            ({{ $books->total() }} found)
        </p>
    @endif

    <!-- Books Grid -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($books as $book)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <!-- Category Badge -->
                        <span class="badge bg-info text-dark mb-2">{{ $book->category }}</span>

                        <!-- Book Title -->
                        <h6 class="card-title fw-bold">{{ $book->title }}</h6>
                        <p class="card-text text-muted small">
                            <i class="bi bi-person"></i> {{ $book->author }}
                        </p>

                        <!-- Description -->
                        @if($book->description)
                            <p class="card-text small">
                                {{ Str::limit($book->description, 80) }}
                            </p>
                        @endif

                        <!-- ISBN -->
                        <p class="small text-muted mb-2">
                            <i class="bi bi-upc"></i> ISBN: {{ $book->isbn }}
                        </p>
                    </div>

                    <!-- Availability Footer -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Total: {{ $book->quantity }} copies</small>
                            <small class="text-muted d-block">Max active books/requests: 3</small>
                        </div>
                        <div class="text-end">
                            @if($book->available_quantity > 0)
                                <span class="badge bg-success mb-2 d-inline-block">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $book->available_quantity }} Available
                                </span>
                            @else
                                <span class="badge bg-danger mb-2 d-inline-block">
                                    <i class="bi bi-x-circle"></i> Not Available
                                </span>
                            @endif

                            <div>
                                @if(in_array($book->id, $activeIssueBookIds))
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="bi bi-hourglass-split"></i> Requested / Issued
                                    </button>
                                @elseif($book->available_quantity < 1)
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-ban"></i> Unavailable
                                    </button>
                                @elseif($activeBorrowCount >= 3)
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-exclamation-circle"></i> Limit Reached
                                    </button>
                                @else
                                    <form action="{{ route('student.books.request', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-journal-plus"></i> Request Issue
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-4">
                    <i class="bi bi-search fs-3"></i><br>
                    No books found matching your search.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="mt-4">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @endif

@endsection
