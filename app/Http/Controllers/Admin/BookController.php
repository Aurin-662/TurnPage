<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = Book::with('author', 'publisher')->orderBy('book_id', 'desc')->get();
        return view('admin.books.index', compact('books'));
    }

    // Show create form
    public function create()
    {
        $authors = Author::orderBy('author_name')->get();
        $publishers = Publisher::orderBy('publisher_name')->get();
        return view('admin.books.create', compact('authors', 'publishers'));
    }

    // Store new book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'author_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'isbn' => 'nullable|string|max:20',
            'page_count' => 'nullable|integer',
            'language' => 'required|string|max:50',
        ]);

        $newId = (Book::max('book_id') ?? 0) + 1;

        Book::create([
            'book_id' => $newId,
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
            'title' => $request->title,
            'isbn' => $request->isbn,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'page_count' => $request->page_count,
            'language' => $request->language,
            'star_rating' => 0,
            'review_count' => 0,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::orderBy('author_name')->get();
        $publishers = Publisher::orderBy('publisher_name')->get();
        return view('admin.books.edit', compact('book', 'authors', 'publishers'));
    }

    // Update book
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'author_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'isbn' => 'nullable|string|max:20',
            'page_count' => 'nullable|integer',
            'language' => 'required|string|max:50',
        ]);

        $book = Book::findOrFail($id);
        $book->update([
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
            'title' => $request->title,
            'isbn' => $request->isbn,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'page_count' => $request->page_count,
            'language' => $request->language,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }

    // Delete book
    public function destroy($id)
    {
        Book::where('book_id', $id)->delete();
        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }
}