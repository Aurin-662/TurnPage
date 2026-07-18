<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured books from database view (highest rated)
        $featuredBooks = DB::table('FEATURED_BOOKS_VIEW')
            ->limit(8)
            ->get();

        // Get best sellers from database view
        $bestSellers = DB::table('BEST_SELLERS_VIEW')
            ->limit(10)
            ->get();

        // Get new arrivals from database view
        $newArrivals = DB::table('NEW_ARRIVALS_VIEW')
            ->limit(8)
            ->get();

        // Get high-rated books
        $topRatedBooks = DB::table('HIGH_RATED_BOOKS_VIEW')
            ->limit(6)
            ->get();

        // Get category statistics
        $categorySummary = DB::table('CATEGORY_SUMMARY_VIEW')
            ->get();

        // Get popular authors for author-driven discovery
        $popularAuthors = DB::table('AUTHOR_BOOK_COUNT_VIEW')
            ->limit(6)
            ->get();

        // Pick a featured deal from the best-rated books
        $dealOfTheDay = DB::table('FEATURED_BOOKS_VIEW')
            ->orderByDesc('star_rating')
            ->first();

        if ($dealOfTheDay) {
            $dealOfTheDay->image = DB::table('BOOK')
                ->where('BOOK_ID', $dealOfTheDay->book_id)
                ->value('IMAGE');
        }

        return view('home', compact('featuredBooks', 'bestSellers', 'newArrivals', 'topRatedBooks', 'categorySummary', 'popularAuthors', 'dealOfTheDay'));
    }
}