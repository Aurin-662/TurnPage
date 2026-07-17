@extends('layouts.app')

@section('title', 'TurnPage — Your Online Bookstore')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
        color: #fff;
        padding: 90px 0 80px;
        text-align: center;
    }
    .hero h1 {
        font-family: 'Merriweather', serif;
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 16px;
    }
    .hero h1 span { color: #e8a045; }
    .hero p { font-size: 1.1rem; color: #b0b8c8; margin-bottom: 32px; }
    .hero .btn-hero {
        background: #e8a045;
        color: #fff;
        border: none;
        padding: 12px 36px;
        font-size: 1rem;
        border-radius: 30px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .hero .btn-hero:hover { background: #d4903a; color: #fff; }

    .section-title {
        font-family: 'Merriweather', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 28px;
    }
    .section-title span { color: #e8a045; }

    .book-card {
        background: #fff;
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover {
        height: 200px;
        background: linear-gradient(135deg, #e8e0d5, #d4c9bb);
        border-radius: 10px 10px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
    .book-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 4px; }
    .book-author { color: #888; font-size: 0.82rem; }
    .book-price { color: #c0392b; font-weight: 700; font-size: 1rem; }
    .star-rating { color: #e8a045; font-size: 0.85rem; }
</style>
@endsection

@section('content')

<!-- Hero -->
<div class="hero reveal">
    <div class="container page-intro">
        <h1>Discover Your Next<br><span>Favorite Book</span></h1>
        <p>Thousands of titles, one click away — TurnPage brings the bookstore to you.</p>
        <a href="{{ route('books.index') }}" class="btn-hero btn-gradient">Browse All Books</a>
    </div>
</div>

<!-- Featured Books -->
<div class="container mt-5 pb-4">
    <h2 class="section-title reveal-on-scroll">⭐ Featured <span>Books</span></h2>

    <div class="row g-4 reveal-on-scroll">
        @foreach($featuredBooks as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover">📖</div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating }}
                        <small class="text-muted">({{ $book->review_count }})</small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="book-price">Tk. {{ number_format($book->price, 0) }}</span>
                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-dark">View</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection