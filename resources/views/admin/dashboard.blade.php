@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')

    <!-- Dashboard Stats Cards -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-book"></i> Total Books</h6>
                    <h3 class="mb-0">{{ $totalBooks }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-people"></i> Total Students</h6>
                    <h3 class="mb-0">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-warning">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-journal-check"></i> Books Issued</h6>
                    <h3 class="mb-0">{{ $totalIssued }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="bi bi-exclamation-triangle"></i> Overdue Books</h6>
                    <h3 class="mb-0">{{ $totalOverdue }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fw-semibold">Quick Actions</div>
                <div class="card-body">
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Add New Book
                    </a>
                    <a href="{{ route('admin.issues.create') }}" class="btn btn-success me-2">
                        <i class="bi bi-journal-plus"></i> Issue a Book
                    </a>
                    <a href="{{ route('admin.issues.overdue') }}" class="btn btn-danger me-2">
                        <i class="bi bi-exclamation-triangle"></i> View Overdue
                    </a>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                        <i class="bi bi-book"></i> Manage Books
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recently Issued Books</span>
            <a href="{{ route('admin.issues.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Student</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentIssues as $index => $issue)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $issue->book->title }}</td>
                                <td>{{ $issue->user->name }}</td>
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
                                <td colspan="6" class="text-center text-muted py-3">No issues found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
