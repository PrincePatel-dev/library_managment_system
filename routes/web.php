<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =====================================================
// Admin Routes (only admin can access)
// =====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Book Management (index, create, store, edit, update, destroy)
    Route::resource('books', BookController::class)->except(['show']);

    // Book Issues
    Route::get('/issues',                    [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/overdue',            [IssueController::class, 'overdue'])->name('issues.overdue');
    Route::get('/issues/create',             [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues',                   [IssueController::class, 'store'])->name('issues.store');
    Route::post('/issues/{issue}/approve',   [IssueController::class, 'approve'])->name('issues.approve');
    Route::post('/issues/{issue}/reject',    [IssueController::class, 'reject'])->name('issues.reject');
    Route::post('/issues/{issue}/return',    [IssueController::class, 'returnBook'])->name('issues.return');

});

// =====================================================
// Student Routes (only students can access)
// =====================================================
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {

    // Student Dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    // Browse all available books with search
    Route::get('/books', [StudentController::class, 'books'])->name('books');
    Route::post('/books/{book}/request', [StudentController::class, 'requestIssue'])->name('books.request');

    // View my borrowed books
    Route::get('/my-books', [StudentController::class, 'myBooks'])->name('my-books');

});
