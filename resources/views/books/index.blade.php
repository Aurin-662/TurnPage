@extends('layouts.app')

@section('title', 'All Books — TurnPage')

@section('styles')
<style>
    .filter-bar { background: #fff; padding: 20px 24px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 28px; }
    .book-card { background: #fff; border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s; height: 100%; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover { height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
    .book-title { font-weight: 600; font-size: 0.95rem; }
    .book-price { color: #c0392b; font-weight: 700; }
    .star-rating { color: #e8a045; font-size: 0.85rem; }
    .result-count { color: #888; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">

    <h2 class="mb-4" style="font-family:'Merriweather',serif;">All Books</h2>

    <!-- Filter Bar -->
    <div class="filter-bar reveal">
        <form action="{{ route('books.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search by title or author..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="language" class="form-select">
                    <option value="">All Languages</option>
                    <option value="Bangla" {{ request('language') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                    <option value="English" {{ request('language') == 'English' ? 'selected' : '' }}>English</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="">Sort By</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4">Apply</button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <p class="result-count mb-3">{{ $books->count() }} book(s) found</p>

    <div class="row g-4 reveal-on-scroll">
        @forelse($books as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover">📖</div>
                <div class="p-3">
                    <p class="book-title mb-1">{{ $book->title }}</p>
                    <p class="text-muted small mb-1">{{ $book->author->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating }}
                        <small class="text-muted">({{ $book->review_count }})</small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="book-price">Tk. {{ number_format($book->price, 0) }}</span>
                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-dark">View</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">No books found.</h5>
            <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">View All Books</a>
        </div>
        @endforelse
    </div>
</div>
@endsection