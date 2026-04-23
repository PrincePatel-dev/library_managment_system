<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    private const MAX_ACTIVE_BORROWS = 3;
    private const ISSUE_DURATION_DAYS = 15;

    // List all issued books
    public function index()
    {
        // Update overdue statuses first
        $this->updateOverdueStatuses();

        $issues = BookIssue::with(['book', 'user'])
                            ->latest()
                            ->paginate(10);

        return view('admin.issues.index', compact('issues'));
    }

    // Show form to issue a book
    public function create()
    {
        // Get all books that have copies available
        $books    = Book::where('available_quantity', '>', 0)->get();
        // Get all students
        $students = User::where('role', 'student')->get();

        return view('admin.issues.create', compact('books', 'students'));
    }

    // Issue a book to a student
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $student = User::where('id', $request->user_id)
                        ->where('role', 'student')
                        ->first();

        if (!$student) {
            return back()->with('error', 'Selected user is not a valid student.');
        }

        $book = Book::findOrFail($request->book_id);

        $activeBorrowCount = BookIssue::where('user_id', $student->id)
                                      ->whereIn('status', ['pending', 'issued', 'overdue'])
                                      ->count();

        if ($activeBorrowCount >= self::MAX_ACTIVE_BORROWS) {
            return back()->with('error', 'This student already has the maximum allowed active books/requests.');
        }

        $hasSameActiveBook = BookIssue::where('user_id', $student->id)
                                      ->where('book_id', $book->id)
                                      ->whereIn('status', ['pending', 'issued', 'overdue'])
                                      ->exists();

        if ($hasSameActiveBook) {
            return back()->with('error', 'This student already has an active request/issue for this book.');
        }

        // Make sure the book is still available
        if ($book->available_quantity < 1) {
            return back()->with('error', 'Sorry, this book is not available right now.');
        }

        // Issue date = today, Due date = fixed 15 days from today
        $issueDate = Carbon::today();
        $dueDate   = Carbon::today()->addDays(self::ISSUE_DURATION_DAYS);

        // Create the issue record
        BookIssue::create([
            'book_id'    => $request->book_id,
            'user_id'    => $request->user_id,
            'issue_date' => $issueDate,
            'due_date'   => $dueDate,
            'status'     => 'issued',
        ]);

        // Decrease available quantity by 1
        $book->decrement('available_quantity');

        return redirect()->route('admin.issues.index')
                         ->with('success', 'Book issued successfully! Due date: ' . $dueDate->format('d M Y'));
    }

    // Approve a student book request
    public function approve(BookIssue $issue)
    {
        if ($issue->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $book = $issue->book;
        if ($book->available_quantity < 1) {
            return back()->with('error', 'This book is no longer available.');
        }

        $activeBorrowCount = BookIssue::where('user_id', $issue->user_id)
                                      ->whereIn('status', ['pending', 'issued', 'overdue'])
                                      ->where('id', '!=', $issue->id)
                                      ->count();

        if ($activeBorrowCount >= self::MAX_ACTIVE_BORROWS) {
            return back()->with('error', 'Student has already reached the maximum allowed active books/requests.');
        }

        $issueDate = Carbon::today();
        $dueDate = Carbon::today()->addDays(self::ISSUE_DURATION_DAYS);

        $issue->update([
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'status' => 'issued',
        ]);

        $book->decrement('available_quantity');

        return back()->with('success', 'Issue request approved successfully.');
    }

    // Reject a student book request
    public function reject(BookIssue $issue)
    {
        if ($issue->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $issue->update([
            'status' => 'rejected',
            'issue_date' => null,
            'due_date' => null,
            'return_date' => null,
        ]);

        return back()->with('success', 'Issue request rejected.');
    }

    // Return a book
    public function returnBook(BookIssue $issue)
    {
        // Make sure the book is still issued (not already returned)
        if (!in_array($issue->status, ['issued', 'overdue'])) {
            return back()->with('error', 'Only issued or overdue books can be marked as returned.');
        }

        // Mark as returned with today's date
        $issue->update([
            'return_date' => Carbon::today(),
            'status'      => 'returned',
        ]);

        // Increase available quantity by 1
        $issue->book->increment('available_quantity');

        return redirect()->route('admin.issues.index')
                         ->with('success', 'Book returned successfully!');
    }

    // Show overdue books
    public function overdue()
    {
        $this->updateOverdueStatuses();

        $overdueIssues = BookIssue::with(['book', 'user'])
                                   ->where('status', 'overdue')
                                   ->latest()
                                   ->paginate(10);

        return view('admin.issues.overdue', compact('overdueIssues'));
    }

    // Helper: Update status of all overdue book issues
    private function updateOverdueStatuses()
    {
        BookIssue::where('status', 'issued')
                  ->whereNotNull('due_date')
                  ->where('due_date', '<', Carbon::today())
                  ->update(['status' => 'overdue']);
    }
}
