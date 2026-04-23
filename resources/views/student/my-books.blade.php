@extends('layouts.app')

@section('title', 'My Borrowed Books')
@section('page-title', 'My Borrowed Books')

@section('content')

    <div class="mb-3">
        <h5>My Borrowed Books</h5>
        <p class="text-muted">View all books you have borrowed and their due dates.</p>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Penalty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $index => $issue)
                            <tr class="{{ $issue->status === 'overdue' ? 'table-danger' : '' }}">
                                <td>{{ $issues->firstItem() + $index }}</td>
                                <td><strong>{{ $issue->book->title }}</strong></td>
                                <td>{{ $issue->book->author }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $issue->book->category }}</span>
                                </td>
                                <td>{{ $issue->issue_date ? $issue->issue_date->format('d M Y') : '-' }}</td>
                                <td>
                                    @if($issue->status === 'pending')
                                        <span class="text-muted">Pending approval</span>
                                    @elseif($issue->status === 'rejected')
                                        <span class="text-muted">Not approved</span>
                                    @elseif($issue->status === 'overdue')
                                        <span class="text-danger fw-bold">
                                            {{ $issue->due_date->format('d M Y') }}
                                            <br><small>({{ $issue->daysOverdue() }} days overdue)</small>
                                        </span>
                                    @else
                                        {{ $issue->due_date ? $issue->due_date->format('d M Y') : '-' }}
                                        @if($issue->status === 'issued')
                                            <br>
                                            @php
                                                $daysLeft = \Carbon\Carbon::today()->diffInDays($issue->due_date, false);
                                            @endphp
                                            @if($daysLeft <= 3 && $daysLeft >= 0)
                                                <small class="text-warning">
                                                    <i class="bi bi-clock"></i> {{ $daysLeft }} days left
                                                </small>
                                            @endif
                                        @endif
                                    @endif
                                </td>
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
                                    @if($issue->status === 'returned')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Returned
                                        </span>
                                    @elseif($issue->status === 'pending')
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-hourglass-split"></i> Pending Approval
                                        </span>
                                    @elseif($issue->status === 'rejected')
                                        <span class="badge bg-dark">
                                            <i class="bi bi-x-circle"></i> Rejected
                                        </span>
                                    @elseif($issue->status === 'overdue')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Overdue
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-journal-check"></i> Issued
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-journal fs-2"></i><br>
                                    You haven't borrowed any books yet.
                                    <a href="{{ route('student.books') }}">Browse available books</a>
                                </td>
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

    <!-- Legend -->
    <div class="mt-3 small text-muted">
        <strong>Legend:</strong>
        <span class="badge bg-secondary ms-1">Pending</span> = Waiting for admin approval
        <span class="badge bg-dark ms-1">Rejected</span> = Request rejected by admin
        <span class="badge bg-warning text-dark ms-1">Issued</span> = Book not yet returned
        <span class="badge bg-danger ms-1">Overdue</span> = Past due date
        <span class="badge bg-success ms-1">Returned</span> = Book returned
    </div>

@endsection
