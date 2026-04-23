@extends('layouts.app')

@section('title', 'Issued Books')
@section('page-title', 'Issued Books')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Book Issues & Requests</h5>
        <a href="{{ route('admin.issues.create') }}" class="btn btn-success">
            <i class="bi bi-journal-plus"></i> Issue a Book
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Student Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Penalty</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $index => $issue)
                            <tr class="{{ $issue->status === 'overdue' ? 'table-danger' : '' }}">
                                <td>{{ $issues->firstItem() + $index }}</td>
                                <td><strong>{{ $issue->book->title }}</strong></td>
                                <td>{{ $issue->user->name }}</td>
                                <td>{{ $issue->issue_date ? $issue->issue_date->format('d M Y') : '-' }}</td>
                                <td>{{ $issue->due_date ? $issue->due_date->format('d M Y') : '-' }}</td>
                                <td>
                                    {{ $issue->return_date ? $issue->return_date->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    @if($issue->penaltyAmount() > 0)
                                        <span class="badge bg-danger">₹{{ $issue->penaltyAmount() }}</span>
                                        <br><small class="text-muted">₹20/day</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
                                <td>
                                    @if($issue->status === 'pending')
                                        <form action="{{ route('admin.issues.approve', $issue->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Approve this issue request?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-check2-circle"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.issues.reject', $issue->id) }}"
                                              method="POST" class="d-inline ms-1"
                                              onsubmit="return confirm('Reject this issue request?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </form>
                                    @elseif(in_array($issue->status, ['issued', 'overdue']))
                                        <form action="{{ route('admin.issues.return', $issue->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Mark this book as returned?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check-circle"></i> Return
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No issued books found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($issues->hasPages())
            <div class="card-footer">
                {{ $issues->links() }}
            </div>
        @endif
    </div>

@endsection
