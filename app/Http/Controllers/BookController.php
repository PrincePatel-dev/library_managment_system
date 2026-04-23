<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    // Show form to add new book
    public function create()
    {
        return view('admin.books.create');
    }

    // Save new book to database
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'isbn'        => 'required|string|unique:books,isbn',
            'quantity'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Create the book - available_quantity starts equal to quantity
        Book::create([
            'title'              => $request->title,
            'author'             => $request->author,
            'category'           => $request->category,
            'isbn'               => $request->isbn,
            'quantity'           => $request->quantity,
            'available_quantity' => $request->quantity,
            'description'        => $request->description,
        ]);

        return redirect()->route('admin.books.index')
                         ->with('success', 'Book added successfully!');
    }

    // Show form to edit book
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    // Update book in database
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'isbn'        => 'required|string|unique:books,isbn,' . $book->id,
            'quantity'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Calculate how many books are currently issued
        $issuedCount = $book->quantity - $book->available_quantity;

        // Update the book details
        $book->update([
            'title'              => $request->title,
            'author'             => $request->author,
            'category'           => $request->category,
            'isbn'               => $request->isbn,
            'quantity'           => $request->quantity,
            'available_quantity' => max(0, $request->quantity - $issuedCount),
            'description'        => $request->description,
        ]);

        return redirect()->route('admin.books.index')
                         ->with('success', 'Book updated successfully!');
    }

    // Delete a book
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')
                         ->with('success', 'Book deleted successfully!');
    }
}
