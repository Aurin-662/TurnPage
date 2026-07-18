<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('author', 'publisher');

        // Search by title or author name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('author_name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Filter by language
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('star_rating', 'desc');
                    break;
                default:
                    $query->orderBy('book_id', 'asc');
            }
        }

        $books = $query->get();
        $featuredBookIds = [1, 4, 6];

        return view('books.index', compact('books', 'featuredBookIds'));
    }

    public function show($id)
    {
        $book = Book::with('author', 'publisher')->findOrFail($id);
        $reviews = \App\Models\Review::with('user')->where('book_id', $id)->orderBy('review_date', 'desc')->get();
        
        // Get related books by same author or publisher, limit to 8
        $relatedBooks = Book::with('author', 'publisher')
            ->where('book_id', '!=', $id)
            ->where(function($query) use ($book) {
                $query->where('author_id', $book->author_id)
                      ->orWhere('publisher_id', $book->publisher_id);
            })
            ->limit(8)
            ->get();
        
        // If no related books by author/publisher, get top rated books
        if ($relatedBooks->count() === 0) {
            $relatedBooks = Book::with('author', 'publisher')
                ->where('book_id', '!=', $id)
                ->orderBy('star_rating', 'DESC')
                ->limit(8)
                ->get();
        }
        
        return view('books.show', compact('book', 'reviews', 'relatedBooks'));
    }
}