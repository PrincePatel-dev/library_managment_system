@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')

    <div class="mb-3">
        <h5>Welcome, {{ auth()->user()->name }}!</h5>
        <p class="text-muted">Here is a summary of your library activity.</p>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-journal-check"></i> Books Currently Borrowed</h6>
                    <h3 class="mb-0">{{ $issuedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-exclamation-triangle"></i> Overdue Books</h6>
                    <h3 class="mb-0">{{ $overdueCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-book"></i> Books You Can Still Borrow</h6>
                    <h3 class="mb-0">{{ 3 - $issuedCount < 0 ? 0 : 3 - $issuedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fw-semibold">Quick Links</div>
                <div class="card-body">
                    <a href="{{ route('student.books') }}" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Browse Books
                    </a>
                    <a href="{{ route('student.my-books') }}" class="btn btn-success">
                        <i class="bi bi-bookmark-check"></i> My Borrowed Books
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Borrows -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">My Recent Borrows</span>
            <a href="{{ route('student.my-books') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBorrows as $index => $issue)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $issue->book->title }}</strong></td>
                                <td>{{ $issue->issue_date ? $issue->issue_date->format('d M Y') : '-' }}</td>
                                <td>{{ $issue->due_date ? $issue->due_date->format('d M Y') : '-' }}</td>
                                <td>
                                    @if($issue->status === 'pending')
                                        <span class="badge bg-secondary">Pending</span>
                                    @elseif($issue->status === 'rejected')
                                        <span class="badge bg-dark">Rejected</span>
                                    @elseif($issue->status === 'returned')
                                        <span class="badge bg-success">Returned</span>
                                    @elseif($issue->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Issued</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    You haven't borrowed any books yet.
                                    <a href="{{ route('student.books') }}">Browse books</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
