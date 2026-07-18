<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $publisherSearch = $request->query('publisher_search');
        $bookSearch = $request->query('book_search');

        $publishersQuery = Publisher::orderBy('publisher_name');

        if ($publisherSearch) {
            $publishersQuery->where('publisher_name', 'like', "%{$publisherSearch}%");
        }

        $publishers = $publishersQuery->take(6)->get();
        $publisherSuggestions = Publisher::orderBy('publisher_name')->pluck('publisher_name');
        $selectedPublisher = null;
        $books = collect();
        $bookSuggestions = collect();

        if ($request->filled('publisher_id')) {
            $selectedPublisher = Publisher::find($request->publisher_id);

            if ($selectedPublisher) {
                $bookSuggestions = $selectedPublisher->books()->orderBy('title')->pluck('title');
                $booksQuery = $selectedPublisher->books()->with('author')->orderBy('title');

                if ($bookSearch) {
                    $booksQuery->where('title', 'like', "%{$bookSearch}%");
                }

                $books = $booksQuery->get();
            }
        }

        return view('publishers.index', compact(
            'publishers',
            'selectedPublisher',
            'books',
            'publisherSearch',
            'bookSearch',
            'publisherSuggestions',
            'bookSuggestions'
        ));
    }
}
