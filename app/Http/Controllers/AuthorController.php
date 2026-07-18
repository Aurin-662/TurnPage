<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authorSearch = $request->query('author_search');
        $bookSearch = $request->query('book_search');

        $authorsQuery = Author::orderBy('author_name');

        if ($authorSearch) {
            $authorsQuery->where('author_name', 'like', "%{$authorSearch}%");
        }

        $authors = $authorsQuery->take(6)->get();
        $authorSuggestions = Author::orderBy('author_name')->pluck('author_name');
        $selectedAuthor = null;
        $books = collect();
        $bookSuggestions = collect();

        if ($request->filled('author_id')) {
            $selectedAuthor = Author::find($request->author_id);

            if ($selectedAuthor) {
                $bookSuggestions = $selectedAuthor->books()->orderBy('title')->pluck('title');
                $booksQuery = $selectedAuthor->books()->with('publisher')->orderBy('title');

                if ($bookSearch) {
                    $booksQuery->where('title', 'like', "%{$bookSearch}%");
                }

                $books = $booksQuery->get();
            }
        }

        return view('authors.index', compact('authors', 'selectedAuthor', 'books', 'authorSearch', 'bookSearch', 'authorSuggestions', 'bookSuggestions'));
    }
}
