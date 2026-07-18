<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index()
    {
        $categories = DB::table('CATEGORY_SUMMARY_VIEW')
            ->orderBy('display_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display books in a specific category with filtering options
     */
    public function show($categoryId)
    {
        $category = DB::table('CATEGORY')
            ->where('category_id', $categoryId)
            ->where('is_active', 1)
            ->firstOrFail();

        // Get all books in this category
        $booksQuery = DB::table('CATEGORY_BOOKS_VIEW')
            ->where('category_id', $categoryId);

        // Apply filters if provided
        if (request('author')) {
            $booksQuery->where('author_name', request('author'));
        }

        if (request('min_price')) {
            $booksQuery->where('price', '>=', request('min_price'));
        }

        if (request('max_price')) {
            $booksQuery->where('price', '<=', request('max_price'));
        }

        if (request('sort') === 'price_low') {
            $booksQuery->orderBy('price', 'ASC');
        } elseif (request('sort') === 'price_high') {
            $booksQuery->orderBy('price', 'DESC');
        } elseif (request('sort') === 'rating') {
            $booksQuery->orderBy('star_rating', 'DESC');
        } else {
            $booksQuery->orderBy('star_rating', 'DESC');
        }

        $books = $booksQuery->paginate(12);
        $books = $this->attachCoverData($books);

        // Get filters for sidebar
        $authors = DB::table('CATEGORY_BOOKS_VIEW')
            ->where('category_id', $categoryId)
            ->distinct()
            ->pluck('author_name');

        $priceRange = DB::table('CATEGORY_BOOKS_VIEW')
            ->where('category_id', $categoryId)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('categories.show', compact('category', 'books', 'authors', 'priceRange'));
    }

    protected function attachCoverData($paginator)
    {
        $collection = $paginator->getCollection();
        $ids = $collection->pluck('book_id')->filter()->unique()->toArray();

        if (empty($ids)) {
            return $paginator;
        }

        try {
            $books = Book::whereIn('book_id', $ids)->get()->keyBy('book_id');
        } catch (\Throwable $e) {
            return $paginator;
        }

        $updated = $collection->map(function ($item) use ($books) {
            $book = $books->get($item->book_id);
            $item->cover_url = $book ? $book->cover_url : null;
            $item->has_cover = !empty($item->cover_url);
            return $item;
        });

        $paginator->setCollection($updated);
        return $paginator;
    }
}
