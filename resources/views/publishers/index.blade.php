@extends('layouts.app')

@section('title', 'Publishers — TurnPage')

@section('styles')
<style>
    .publishers-header { background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%); color: #fff; padding: 42px 0; border-radius: 16px; margin-bottom: 30px; }
    .publishers-header h2 { font-family: 'Merriweather', serif; margin-bottom: 8px; }
    .publisher-card, .publisher-books-card, .publisher-sidebar { background: #fff; border-radius: 16px; box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
    .publisher-card { padding: 18px; display: flex; align-items: center; gap: 16px; }
    .publisher-avatar { width: 76px; height: 76px; border-radius: 18px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); display: flex; align-items: center; justify-content: center; font-size: 2.4rem; color: #4b5563; }
    .publisher-title { font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
    .publisher-meta { color: #6b7280; margin-bottom: 0; }
    .publisher-sidebar { padding: 24px; }
    .publisher-item { border-bottom: 1px solid #f0f0f0; padding: 16px 0; }
    .publisher-item:last-child { border-bottom: none; }
    .publisher-item a { display: inline-flex; align-items: center; gap: 8px; color: #1a1a2e; text-decoration: none; font-weight: 600; }
    .publisher-item a:hover { color: #0f3460; }
    .search-input { border-radius: 12px 0 0 12px; }
    .search-button { border-radius: 0 12px 12px 0; }
    .search-note { color: #6b7280; font-size: 0.85rem; margin-bottom: 16px; }
    .book-card { background: #fff; border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover { height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem; position: relative; overflow: hidden; }
    .book-cover img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .book-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 0.5rem; }
    .book-meta { color: #6b7280; font-size: 0.82rem; margin-bottom: 0.75rem; }
    .book-price { color: #c0392b; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <div class="publishers-header">
        <div class="container">
            <h2>🏢 Publishers</h2>
            <p>Choose a publisher to view their available books.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="publisher-sidebar">
                <h5 class="mb-3">Publisher List</h5>
                <form method="GET" action="{{ route('publishers.index') }}" class="mb-3">
                    <input type="hidden" name="publisher_id" value="{{ $selectedPublisher->publisher_id ?? '' }}">
                    <div class="input-group">
                        <input type="search" name="publisher_search" value="{{ $publisherSearch ?? '' }}" class="form-control search-input" placeholder="Search publishers..." list="publisher-suggestions">
                        <button type="submit" class="btn btn-dark search-button">Search</button>
                    </div>
                    <datalist id="publisher-suggestions">
                        @foreach($publisherSuggestions as $suggestion)
                            <option value="{{ $suggestion }}"></option>
                        @endforeach
                    </datalist>
                </form>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="search-note mb-0">Showing up to 6 publishers in the list. Use search to find a specific publisher.</p>
                    <a href="{{ route('publishers.index', array_filter(['publisher_id' => $selectedPublisher->publisher_id ?? null])) }}" class="btn btn-outline-secondary btn-sm">Clear search</a>
                </div>
                @foreach($publishers as $publisher)
                    <div class="publisher-item">
                        <a href="{{ route('publishers.index', array_filter(['publisher_id' => $publisher->publisher_id, 'publisher_search' => $publisherSearch ?? null])) }}">
                            <span>🏢</span>
                            {{ $publisher->publisher_name }}
                        </a>
                        <p class="publisher-meta mb-0">{{ $publisher->address ?? 'Publisher details not available.' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-xl-8">
            @if($selectedPublisher)
                <div class="publisher-card mb-4">
                    <div class="publisher-avatar">🏛️</div>
                    <div>
                        <h3 class="publisher-title mb-1">{{ $selectedPublisher->publisher_name }}</h3>
                        <p class="publisher-meta mb-1">{{ $selectedPublisher->address ?? 'Address not available' }}</p>
                        <p class="publisher-meta mb-0">{{ $selectedPublisher->email ?? 'Contact info not available' }}</p>
                    </div>
                </div>

                <div class="publisher-books-card p-3">
                    <h5 class="mb-3">Books from {{ $selectedPublisher->publisher_name }}</h5>
                    <form method="GET" action="{{ route('publishers.index') }}" class="mb-4">
                        <input type="hidden" name="publisher_id" value="{{ $selectedPublisher->publisher_id }}">
                        <input type="hidden" name="publisher_search" value="{{ $publisherSearch ?? '' }}">
                        <div class="input-group">
                            <input type="search" name="book_search" value="{{ $bookSearch ?? '' }}" class="form-control search-input" placeholder="Search books by title..." list="book-suggestions">
                            <button type="submit" class="btn btn-dark search-button">Search</button>
                        </div>
                        <datalist id="book-suggestions">
                            @foreach($bookSuggestions as $suggestion)
                                <option value="{{ $suggestion }}"></option>
                            @endforeach
                        </datalist>
                    </form>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('publishers.index', array_filter(['publisher_id' => $selectedPublisher->publisher_id, 'publisher_search' => $publisherSearch ?? null])) }}" class="btn btn-outline-secondary btn-sm">Clear book search</a>
                    </div>
                    @if($books->count() > 0)
                        <div class="row g-4">
                            @foreach($books as $book)
                                <div class="col-md-6">
                                    <div class="book-card">
                                        <div class="book-cover">
                                            <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); position: absolute; inset: 0;">
                                                📖
                                            </div>
                                            @if($book->has_cover)
                                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" onerror="this.onerror=null;this.style.display='none'">
                                            @endif
                                        </div>
                                        <div class="p-3">
                                            <p class="book-title">{{ $book->title }}</p>
                                            <p class="book-meta mb-2">{{ $book->author->author_name ?? 'Unknown author' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="book-price">Tk. {{ number_format($book->price, 0) }}</span>
                                                <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-dark">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No books found for this publisher.</p>
                    @endif
                </div>
            @else
                <div class="publisher-card p-4 text-center">
                    <h5>Select a publisher from the list to see their books.</h5>
                    <p class="text-muted mb-0">You can browse the publisher list and choose any publisher to view available titles.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
