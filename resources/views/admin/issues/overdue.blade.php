@extends('layouts.app')

@section('title', 'Overdue Books')
@section('page-title', 'Overdue Books')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-danger">
            <i class="bi bi-exclamation-triangle"></i> Overdue Books
        </h5>
        <a href="{{ route('admin.issues.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to All Issues
        </a>
    </div>

    @if($overdueIssues->count() > 0)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle"></i>
            <strong>{{ $overdueIssues->total() }} overdue record(s)</strong> found.
            Please contact the students to return the books.
        </div>
    @endif

    <div class="card border-danger">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Student</th>
                            <th>Student Email</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th>Penalty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueIssues as $index => $issue)
                            <tr>
                                <td>{{ $overdueIssues->firstItem() + $index }}</td>
                                <td><strong>{{ $issue->book->title }}</strong></td>
                                <td>{{ $issue->user->name }}</td>
                                <td><small>{{ $issue->user->email }}</small></td>
                                <td>{{ $issue->issue_date->format('d M Y') }}</td>
                                <td class="text-danger fw-bold">{{ $issue->due_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-danger fs-6">
                                        {{ $issue->daysOverdue() }} days
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-danger fs-6">₹{{ $issue->penaltyAmount() }}</span>
                                    <br><small class="text-muted">₹20/day</small>
                                </td>
                                <td>
                                    <form action="{{ route('admin.issues.return', $issue->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Mark this book as returned?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check-circle"></i> Mark Returned
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle text-success fs-3"></i><br>
                                    No overdue books. All books are returned on time!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($overdueIssues->hasPages())
            <div class="card-footer">
                {{ $overdueIssues->links() }}
            </div>
        @endif
    </div>

@endsection
