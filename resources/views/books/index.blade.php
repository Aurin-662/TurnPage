@extends('layouts.app')

@section('title', 'All Books — TurnPage')

@section('styles')
<style>
    .page-header { background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%); color: #fff; padding: 36px 0; border-radius: 16px; margin-bottom: 28px; }
    .page-header h2 { font-family: 'Merriweather', serif; margin-bottom: 6px; }
    .page-header p { color: #d3dce9; margin-bottom: 0; }
    .filter-bar { background: #fff; padding: 20px 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 28px; }
    .book-card { background: #fff; border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover { height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
    .book-title { font-weight: 600; font-size: 0.95rem; }
    .book-price { color: #c0392b; font-weight: 700; }
    .star-rating { color: #e8a045; font-size: 0.85rem; }
    .result-count { color: #888; font-size: 0.9rem; }
    .book-badge { display: inline-block; background: #e8a045; color: #fff; padding: 5px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 10px; }
    .book-meta { color: #6b7280; font-size: 0.82rem; }
    .side-card { background: #fff; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 18px; }
    .side-card h5 { font-family: 'Merriweather', serif; font-size: 1rem; margin-bottom: 12px; color: #1a1a2e; }
    .side-link { display: block; color: #374151; text-decoration: none; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .side-link:last-child { border-bottom: none; }
    .side-link:hover { color: #e8a045; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">

    <div class="page-header">
        <div class="container">
            <h2>📚 All Books</h2>
            <p>Search, filter, and discover your next favorite read.</p>
        </div>
    </div>

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

    <div class="row g-4">
        <div class="col-lg-3 order-lg-2">
            <div class="side-card reveal-on-scroll">
                <h5>Featured Categories</h5>
                <a href="{{ route('categories.show', 1) }}" class="side-link">📖 Fiction</a>
                <a href="{{ route('categories.show', 2) }}" class="side-link">📙 Non-Fiction</a>
                <a href="{{ route('categories.show', 3) }}" class="side-link">🌟 Self-Help</a>
                <a href="{{ route('categories.show', 5) }}" class="side-link">💻 Technology</a>
            </div>

            <div class="side-card reveal-on-scroll">
                <h5>Top Picks</h5>
                <a href="{{ route('books.show', 6) }}" class="side-link">The Alchemist</a>
                <a href="{{ route('books.show', 4) }}" class="side-link">Harry Potter and the Philosopher's Stone</a>
                <a href="{{ route('books.show', 1) }}" class="side-link">Himu</a>
            </div>
        </div>

        <div class="col-lg-9 order-lg-1 reveal-on-scroll">
            <div class="row g-4">
                @forelse($books as $book)
                <div class="col-md-4">
                    <div class="book-card">
                        <div class="book-cover overflow-hidden" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); position: absolute; inset: 0;">
                                📖
                            </div>
                            @if($book->has_cover)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; position: absolute; inset: 0;" onerror="this.onerror=null;this.style.display='none'">
                            @endif
                        </div>
                        <div class="p-3">
                            @if(in_array((int) $book->book_id, $featuredBookIds ?? []))
                                <span class="book-badge">Popular</span>
                            @endif
                            <p class="book-title mb-1">{{ $book->title }}</p>
                            <p class="book-meta mb-1">{{ $book->author->author_name ?? 'Unknown' }}</p>
                            <p class="star-rating mb-2">★ {{ $book->star_rating }}
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
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
    });
</script>
@endsection