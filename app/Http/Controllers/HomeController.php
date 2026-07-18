<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Allow the UI to request how many featured books to show (default 8).
        $requestedCount = (int) (request()->query('featured_count', 8));
        $featuredCount = max(4, min(12, $requestedCount));

        $featuredBooks = $this->fetchViewData('FEATURED_BOOKS_VIEW', function () use ($featuredCount) {
            try {
                return Book::with('author', 'publisher')
                    ->where('stock_quantity', '>', 0)
                    ->where('star_rating', '>=', 3.5)
                    ->orderByDesc('star_rating')
                    ->orderByDesc('review_count')
                    ->limit($featuredCount)
                    ->get()
                    ->map(function ($book) {
                        return (object) [
                            'book_id' => $book->book_id,
                            'title' => $book->title,
                            'price' => $book->price,
                            'star_rating' => $book->star_rating,
                            'review_count' => $book->review_count,
                            'stock_quantity' => $book->stock_quantity,
                            'author_name' => $book->author->author_name ?? null,
                            'publisher_name' => $book->publisher->publisher_name ?? null,
                            'image' => $book->image,
                        ];
                    });
            } catch (\Throwable $e) {
                return collect();
            }
        });

        // Ensure we only expose the requested number even when the view returns more rows
        $featuredBooks = collect($featuredBooks)->take($featuredCount);

        if ($featuredBooks->isEmpty()) {
            $featuredBooks = $this->getFallbackBooks($featuredCount);
        }

        $featuredBooks = $this->attachCoverData($featuredBooks);

        $bestSellers = $this->fetchViewData('BEST_SELLERS_VIEW', function () {
            try {
                return Book::with('author', 'publisher')
                    ->where('stock_quantity', '>', 0)
                    ->orderByDesc('star_rating')
                    ->limit(10)
                    ->get()
                    ->map(function ($book) {
                        return (object) [
                            'book_id' => $book->book_id,
                            'title' => $book->title,
                            'price' => $book->price,
                            'star_rating' => $book->star_rating,
                            'author_name' => $book->author->author_name ?? null,
                        ];
                    });
            } catch (\Throwable $e) {
                return collect();
            }
        });

        if ($bestSellers->isEmpty()) {
            $bestSellers = $this->getFallbackBooks(6);
        }

        $bestSellers = $this->attachCoverData($bestSellers);

        $newArrivals = $this->fetchViewData('NEW_ARRIVALS_VIEW', function () {
            try {
                return Book::with('author')
                    ->where('stock_quantity', '>', 0)
                    ->orderByDesc('book_id')
                    ->limit(8)
                    ->get()
                    ->map(function ($book) {
                        return (object) [
                            'book_id' => $book->book_id,
                            'title' => $book->title,
                            'price' => $book->price,
                            'star_rating' => $book->star_rating,
                            'author_name' => $book->author->author_name ?? null,
                        ];
                    });
            } catch (\Throwable $e) {
                return collect();
            }
        });

        if ($newArrivals->isEmpty()) {
            $newArrivals = $this->getFallbackBooks(4);
        }

        $newArrivals = $this->attachCoverData($newArrivals);

        $topRatedBooks = $this->fetchViewData('HIGH_RATED_BOOKS_VIEW', function () {
            try {
                return Book::with('author')
                    ->where('stock_quantity', '>', 0)
                    ->orderByDesc('star_rating')
                    ->limit(6)
                    ->get()
                    ->map(function ($book) {
                        return (object) [
                            'book_id' => $book->book_id,
                            'title' => $book->title,
                            'price' => $book->price,
                            'star_rating' => $book->star_rating,
                            'review_count' => $book->review_count,
                            'author_name' => $book->author->author_name ?? null,
                        ];
                    });
            } catch (\Throwable $e) {
                return collect();
            }
        });

        if ($topRatedBooks->isEmpty()) {
            $topRatedBooks = $this->getFallbackBooks(6);
        }

        $topRatedBooks = $this->attachCoverData($topRatedBooks);

        $categorySummary = $this->fetchViewData('CATEGORY_SUMMARY_VIEW', function () {
            try {
                return $this->buildCategorySummaryFromDatabase();
            } catch (\Throwable $e) {
                return collect();
            }
        });

        $categorySummary = $this->normalizeCategorySummary($categorySummary);

        $popularAuthors = $this->fetchViewData('AUTHOR_BOOK_COUNT_VIEW', function () {
            try {
                return DB::table('AUTHOR as a')
                    ->leftJoin('BOOK as b', 'a.AUTHOR_ID', '=', 'b.AUTHOR_ID')
                    ->select(
                        'a.AUTHOR_ID as author_id',
                        'a.AUTHOR_NAME as author_name',
                        'a.COUNTRY as country',
                        DB::raw('COUNT(b.BOOK_ID) AS total_books'),
                        DB::raw('ROUND(AVG(b.PRICE), 2) AS avg_book_price'),
                        DB::raw('ROUND(AVG(b.STAR_RATING), 2) AS avg_rating')
                    )
                    ->groupBy('a.AUTHOR_ID', 'a.AUTHOR_NAME', 'a.COUNTRY')
                    ->havingRaw('COUNT(b.BOOK_ID) > 0')
                    ->orderByDesc('total_books')
                    ->limit(6)
                    ->get();
            } catch (\Throwable $e) {
                return collect();
            }
        });

        $dealOfTheDay = $featuredBooks->first();

        if (!$dealOfTheDay && $topRatedBooks->count()) {
            $dealOfTheDay = $topRatedBooks->first();
        }

        if ($dealOfTheDay && !isset($dealOfTheDay->image)) {
            try {
                $dealOfTheDay->image = DB::table('BOOK')
                    ->where('BOOK_ID', $dealOfTheDay->book_id)
                    ->value('IMAGE');
            } catch (\Throwable $e) {
                $dealOfTheDay->image = null;
            }
        }

        return view('home', compact(
            'featuredBooks',
            'bestSellers',
            'newArrivals',
            'topRatedBooks',
            'categorySummary',
            'popularAuthors',
            'dealOfTheDay'
        ));
    }

    protected function buildCategorySummaryFromDatabase()
    {
        return DB::table('CATEGORY as c')
            ->leftJoin('BOOK_CATEGORY as bc', 'c.CATEGORY_ID', '=', 'bc.CATEGORY_ID')
            ->leftJoin('BOOK as b', 'bc.BOOK_ID', '=', 'b.BOOK_ID')
            ->where('c.IS_ACTIVE', 1)
            ->select(
                'c.CATEGORY_ID as category_id',
                'c.CATEGORY_NAME as category_name',
                'c.ICON as icon',
                'c.DISPLAY_ORDER as display_order',
                DB::raw('COUNT(DISTINCT bc.BOOK_ID) AS total_books'),
                DB::raw('COALESCE(SUM(b.STOCK_QUANTITY), 0) AS total_stock'),
                DB::raw('ROUND(AVG(b.PRICE), 2) AS avg_price'),
                DB::raw('MIN(b.PRICE) AS min_price'),
                DB::raw('MAX(b.PRICE) AS max_price')
            )
            ->groupBy('c.CATEGORY_ID', 'c.CATEGORY_NAME', 'c.ICON', 'c.DISPLAY_ORDER')
            ->orderBy('c.DISPLAY_ORDER')
            ->get();
    }

    protected function normalizeCategorySummary($categorySummary)
    {
        $collection = collect($categorySummary);

        return $collection->map(function ($item) {
            $normalized = new \stdClass();
            $normalized->category_id = $item->category_id ?? $item->CATEGORY_ID ?? null;
            $normalized->category_name = $item->category_name ?? $item->CATEGORY_NAME ?? null;
            $normalized->icon = $item->icon ?? $item->ICON ?? null;
            $normalized->display_order = $item->display_order ?? $item->DISPLAY_ORDER ?? 999;
            $normalized->total_books = (int) ($item->total_books ?? $item->TOTAL_BOOKS ?? 0);
            $normalized->total_stock = $item->total_stock ?? $item->TOTAL_STOCK ?? 0;
            $normalized->avg_price = $item->avg_price ?? $item->AVG_PRICE ?? null;
            $normalized->min_price = $item->min_price ?? $item->MIN_PRICE ?? null;
            $normalized->max_price = $item->max_price ?? $item->MAX_PRICE ?? null;

            return $normalized;
        })->values();
    }

    protected function attachCoverData($collection)
    {
        $ids = $collection->pluck('book_id')->filter()->unique()->toArray();

        if (empty($ids)) {
            return $collection;
        }

        try {
            $books = Book::whereIn('book_id', $ids)->get()->keyBy('book_id');
        } catch (\Throwable $e) {
            return $collection;
        }

        return $collection->map(function ($item) use ($books) {
            $book = $books->get($item->book_id);
            $item->cover_url = $book ? $book->cover_url : null;
            $item->has_cover = $book ? $book->has_cover : false;
            return $item;
        });
    }

    protected function getFallbackBooks(int $count = 8)
    {
        $items = [
            ['book_id' => 101, 'title' => 'The Hobbit', 'price' => 720, 'star_rating' => 4.7, 'review_count' => 128, 'author_name' => 'J.R.R. Tolkien', 'publisher_name' => 'Penguin Books'],
            ['book_id' => 102, 'title' => '1984', 'price' => 660, 'star_rating' => 4.8, 'review_count' => 112, 'author_name' => 'George Orwell', 'publisher_name' => 'Penguin Books'],
            ['book_id' => 103, 'title' => 'Pride and Prejudice', 'price' => 690, 'star_rating' => 4.6, 'review_count' => 97, 'author_name' => 'Jane Austen', 'publisher_name' => 'Oxford Press'],
            ['book_id' => 104, 'title' => 'The Alchemist', 'price' => 550, 'star_rating' => 4.5, 'review_count' => 89, 'author_name' => 'Paulo Coelho', 'publisher_name' => 'HarperCollins'],
            ['book_id' => 105, 'title' => 'The Kite Runner', 'price' => 700, 'star_rating' => 4.7, 'review_count' => 104, 'author_name' => 'Khaled Hosseini', 'publisher_name' => 'Riverhead'],
            ['book_id' => 106, 'title' => 'A Game of Thrones', 'price' => 920, 'star_rating' => 4.9, 'review_count' => 156, 'author_name' => 'George R. R. Martin', 'publisher_name' => 'Bantam'],
        ];

        return collect($items)->take($count)->map(function ($item) {
            return (object) array_merge($item, [
                'stock_quantity' => 10,
                'has_cover' => false,
                'cover_url' => null,
            ]);
        });
    }

    protected function fetchViewData(string $viewName, callable $fallback)
    {
        try {
            $data = DB::table($viewName)->get();

            if ($data->isEmpty()) {
                return collect($fallback());
            }

            return $data;
        } catch (\Throwable $e) {
            try {
                return collect($fallback());
            } catch (\Throwable $fallbackException) {
                return collect();
            }
        }
    }
}