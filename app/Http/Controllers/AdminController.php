<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Admin dashboard - show summary cards
    public function dashboard()
    {
        // Count totals for dashboard cards
        $totalBooks      = Book::count();
        $totalStudents   = User::where('role', 'student')->count();
        $totalIssued     = BookIssue::where('status', 'issued')->count();
        $totalOverdue    = BookIssue::where('status', 'overdue')->count();

        // Recent issued books (last 5)
        $recentIssues = BookIssue::with(['book', 'user'])
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalStudents',
            'totalIssued',
            'totalOverdue',
            'recentIssues'
        ));
    }
}
