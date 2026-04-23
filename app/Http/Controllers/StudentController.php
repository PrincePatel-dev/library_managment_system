<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private const MAX_ACTIVE_BORROWS = 3;

    // Student dashboard
    public function dashboard()
    {
        $student = auth()->user();

        // Count this student's issued books
        $issuedCount   = BookIssue::where('user_id', $student->id)
                                   ->whereIn('status', ['pending', 'issued', 'overdue'])
                                   ->count();

        // Count overdue books for this student
        $overdueCount  = BookIssue::where('user_id', $student->id)
                                   ->where('status', 'overdue')
                                   ->count();

        // Recent borrows
        $recentBorrows = BookIssue::with('book')
                                   ->where('user_id', $student->id)
                                   ->latest()
                                   ->take(5)
                                   ->get();

        return view('student.dashboard', compact('issuedCount', 'overdueCount', 'recentBorrows'));
    }

    // View all available books with search
    public function books(Request $request)
    {
        $student = auth()->user();

        // Build the query for books
        $query = Book::query();

        // Search by title or author if search term is provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(12);

        $activeIssueBookIds = BookIssue::where('user_id', $student->id)
                                    ->whereIn('status', ['pending', 'issued', 'overdue'])
                                    ->pluck('book_id')
                                    ->toArray();

        $activeBorrowCount = BookIssue::where('user_id', $student->id)
                                      ->whereIn('status', ['pending', 'issued', 'overdue'])
                                      ->count();

        return view('student.books', compact('books', 'activeIssueBookIds', 'activeBorrowCount'));
    }

    // Request a book issue (student side)
    public function requestIssue(Book $book)
    {
        $student = auth()->user();

        if ($book->available_quantity < 1) {
            return back()->with('error', 'This book is not available right now.');
        }

        $activeBorrowCount = BookIssue::where('user_id', $student->id)
                                      ->whereIn('status', ['pending', 'issued', 'overdue'])
                                      ->count();

        if ($activeBorrowCount >= self::MAX_ACTIVE_BORROWS) {
            return back()->with('error', 'You can only have up to 3 active books/requests at a time.');
        }

        $alreadyRequested = BookIssue::where('user_id', $student->id)
                                     ->where('book_id', $book->id)
                                     ->whereIn('status', ['pending', 'issued', 'overdue'])
                                     ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'You already have an active request/issue for this book.');
        }

        BookIssue::create([
            'book_id' => $book->id,
            'user_id' => $student->id,
            'issue_date' => null,
            'due_date' => null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Book request submitted. Waiting for admin approval.');
    }

    // View books the student has borrowed
    public function myBooks()
    {
        $student = auth()->user();

        $issues = BookIssue::with('book')
                            ->where('user_id', $student->id)
                            ->latest()
                            ->paginate(10);

        return view('student.my-books', compact('issues'));
    }
}
