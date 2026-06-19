<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Show all books
    public function index()
    {
        $books = Book::with('author', 'publisher')->get();
        return view('books.index', compact('books'));
    }

    // Show single book details
    public function show($id)
    {
        $book = Book::with('author', 'publisher')->findOrFail($id);
        return view('books.show', compact('book'));
    }
}