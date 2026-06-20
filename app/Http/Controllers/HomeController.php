<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get top rated books for "Featured" section
        $featuredBooks = Book::with('author')
                              ->orderBy('star_rating', 'desc')
                              ->take(8)
                              ->get();

        return view('home', compact('featuredBooks'));
    }
}