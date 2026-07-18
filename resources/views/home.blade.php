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
        transition: transform 0.2s, background 0.2s;
    }
    .hero .btn-hero:hover { background: #d4903a; color: #fff; transform: translateY(-2px); }
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        color: #f4e6bc;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.9rem;
        margin-bottom: 18px;
        backdrop-filter: blur(6px);
    }
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 28px;
    }
    .hero-stat {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.16);
        border-radius: 14px;
        padding: 12px 16px;
        min-width: 120px;
    }
    .hero-stat strong { display: block; font-size: 1.1rem; color: #fff; }
    .hero-stat span { font-size: 0.85rem; color: #cfd8e3; }

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
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover {
        height: 200px;
        background: linear-gradient(135deg, #e8e0d5, #d4c9bb);
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        position: relative;
    }
    .book-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #e8a045;
        color: #fff;
        font-size: 0.72rem;
        padding: 5px 9px;
        border-radius: 999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .book-stock {
        color: #2e7d32;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .book-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 4px; }
    .book-author { color: #888; font-size: 0.82rem; }
    .book-price { color: #c0392b; font-weight: 700; font-size: 1rem; }
    .star-rating { color: #e8a045; font-size: 0.85rem; }

    .section-subtitle {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 24px;
    }

    .category-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        height: 100%;
    }
    .category-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }

    .feature-card {
        background: linear-gradient(145deg, #ffffff, #f8f3ea);
        border: 1px solid #eee2d0;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        height: 100%;
    }
    .feature-card h5 { font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
    .feature-card p { color: #6b7280; margin-bottom: 0; font-size: 0.95rem; }

    .trust-banner {
        background: linear-gradient(135deg, #f8f5ee 0%, #f1e9db 100%);
        border: 1px solid #e9dfcf;
        border-radius: 20px;
        padding: 28px 30px;
        box-shadow: 0 6px 20px rgba(17, 24, 39, 0.04);
    }
    .trust-banner h3 {
        font-family: 'Merriweather', serif;
        color: #1a1a2e;
        margin-bottom: 8px;
        font-size: 1.2rem;
    }
    .trust-banner p {
        color: #6b7280;
        margin-bottom: 0;
    }
    .promo-badge {
        display: inline-block;
        background: linear-gradient(135deg, #f7c36a 0%, #e8a045 100%);
        color: #111827;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        box-shadow: 0 6px 16px rgba(232, 160, 69, 0.22);
    }
    .deal-hero {
        position: relative;
        display: grid;
        grid-template-columns: 150px minmax(0, 1fr);
        gap: 20px;
        background: linear-gradient(135deg, #111827 0%, #1f2937 55%, #263449 100%);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 28px;
        padding: 22px;
        color: #fff;
        box-shadow: 0 16px 36px rgba(17, 24, 39, 0.16);
        align-items: center;
        overflow: hidden;
    }
    .deal-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(232, 160, 69, 0.18), transparent 38%);
        pointer-events: none;
    }
    .deal-hero-content {
        position: relative;
        min-width: 0;
    }
    .deal-hero-content h3 {
        font-family: 'Merriweather', serif;
        font-size: 1.55rem;
        margin-bottom: 10px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }
    .deal-hero-content p {
        color: #dce3ef;
        margin-bottom: 10px;
        font-size: 0.95rem;
        line-height: 1.55;
    }
    .deal-topline {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }
    .deal-topline span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #d8e2f0;
        font-size: 0.9rem;
    }
    .deal-meta-stack {
        display: grid;
        gap: 8px;
        margin: 12px 0 14px;
    }
    .deal-price-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        padding: 8px 12px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.16);
        border-radius: 999px;
    }
    .deal-price-label {
        color: #f7d9a2;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .deal-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .deal-cover {
        position: relative;
        width: 150px;
        height: 210px;
        border-radius: 22px;
        background: #f8f8f8;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 24px rgba(0,0,0,0.18);
    }
    .deal-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .deal-cover-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #6b7280;
        font-size: 3.2rem;
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    }
    .deal-cover-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 9px;
        border-radius: 999px;
        background: rgba(17,24,39,0.82);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .deal-price-pill {
        background: #fff;
        color: #111827;
        padding: 7px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.92rem;
        white-space: nowrap;
    }
    .deal-pill-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    .deal-pill-row span {
        background: rgba(255,255,255,0.12);
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        color: #f5f7fa;
        border: 1px solid rgba(255,255,255,0.12);
    }
    @media (max-width: 960px) {
        .deal-hero {
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 18px;
        }
        .deal-cover {
            width: 100%;
            height: 180px;
            margin: 0 auto;
        }
        .deal-hero-content h3 {
            font-size: 1.3rem;
        }
        .deal-hero-content p,
        .deal-price-pill,
        .deal-pill-row span {
            font-size: 0.85rem;
        }
        .deal-hero-actions {
            justify-content: flex-start;
        }
    }
    .author-panel {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        gap: 10px;
    }
    .author-panel .author-list {
        display: grid;
        gap: 10px;
        margin-top: 4px;
    }
    .author-panel .author-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 12px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f0e4d3;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }
    .author-panel .author-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.06);
    }
    .author-panel .author-name {
        font-weight: 700;
        color: #111827;
    }
    .author-panel .author-meta {
        color: #6b7280;
        font-size: 0.83rem;
    }
    .author-panel .btn-outline-dark {
        border-color: #d1d5db;
        color: #111827;
    }
    .author-panel .btn-outline-dark:hover {
        background: #111827;
        color: #fff;
        border-color: #111827;
    }
    .author-panel-footer {
        margin-top: auto;
        padding-top: 8px;
        border-top: 1px solid #efe4d3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }
    .author-footer-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: #f7efe4;
        color: #8a5b19;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .deal-hero .btn-outline-light {
        color: #f8fafc;
        border-color: rgba(248,250,252,0.26);
    }
    .deal-hero .btn-outline-light:hover {
        background: rgba(248,250,252,0.08);
        border-color: rgba(248,250,252,0.38);
    }

    .reveal-on-scroll {
        opacity: 0;
        transform: translateY(18px);
        transition: all 0.6s ease;
    }
    .reveal-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .category-icon { font-size: 2.5rem; margin-bottom: 12px; }
    .category-name { font-weight: 600; margin-bottom: 8px; }
    .category-count { font-size: 0.85rem; color: #888; }

    .section-spacing { margin-bottom: 60px; }
</style>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Use IntersectionObserver when available, otherwise reveal immediately.
        const revealElements = Array.from(document.querySelectorAll('.reveal-on-scroll'));

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            revealElements.forEach(el => observer.observe(el));

            // Safety net: if observer didn't mark elements after a short delay, reveal them.
            setTimeout(() => {
                revealElements.forEach(el => {
                    if (!el.classList.contains('is-visible')) {
                        el.classList.add('is-visible');
                    }
                });
            }, 300);
        } else {
            // Fallback for very old browsers or environments without IntersectionObserver
            revealElements.forEach(el => el.classList.add('is-visible'));
        }
    });
</script>
@endsection

@section('content')

<!-- Hero -->
<div class="hero">
    <div class="container page-intro">
        <div class="hero-badge">📚 Curated for readers, powered by smart database features</div>
        <h1>Discover Your Next<br><span>Favorite Book</span></h1>
        <p>Thousands of titles, one click away — TurnPage brings the bookstore to you.</p>
        <a href="{{ route('books.index') }}" class="btn-hero">Browse All Books</a>

        <div class="hero-stats">
            <div class="hero-stat">
                <strong>7+</strong>
                <span>Categories</span>
            </div>
            <div class="hero-stat">
                <strong>24/7</strong>
                <span>Easy browsing</span>
            </div>
            <div class="hero-stat">
                <strong>Fast</strong>
                <span>Checkout flow</span>
            </div>
        </div>
    </div>
</div>

<!-- Why TurnPage -->
<div class="container section-spacing mt-5">
    <div class="row g-4 reveal-on-scroll">
        <div class="col-md-4">
            <div class="feature-card">
                <h5>Smart Discovery</h5>
                <p>Explore featured picks, top-rated books, and personalized browsing in one place.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <h5>Seamless Shopping</h5>
                <p>From book selection to cart and orders, the experience is simple and smooth.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <h5>Reliable Experience</h5>
                <p>Clean layouts, responsive cards, and polished interactions make the store feel premium.</p>
            </div>
        </div>
    </div>
</div>

<!-- Deal + Authors Section -->
<div class="container section-spacing mt-4">
    <div class="row g-4 reveal-on-scroll align-items-start">
        <div class="col-lg-8">
            @if($dealOfTheDay)
            <div class="deal-hero">
                <div class="deal-cover">
                    <div class="deal-cover-badge">Today’s Pick</div>
                    @php
                        $dealImage = $dealOfTheDay->cover_url ?? $dealOfTheDay->image ?? null;
                    @endphp
                    @if(!empty($dealImage))
                        <img src="{{ $dealImage }}" alt="{{ $dealOfTheDay->title }} cover">
                    @else
                        <div class="deal-cover-placeholder">📖</div>
                    @endif
                </div>
                <div class="deal-hero-content">
                    <div class="promo-badge">Deal of the Day</div>
                    <h3>{{ $dealOfTheDay->title }}</h3>
                    <div class="deal-topline">
                        <span>✍️ {{ $dealOfTheDay->author_name ?? 'Popular author' }}</span>
                        <span>⭐ {{ number_format($dealOfTheDay->star_rating ?? 0, 1) }}/5</span>
                    </div>
                    <div class="deal-meta-stack">
                        <div class="deal-price-box">
                            <span class="deal-price-label">Special Price</span>
                            <span class="deal-price-pill">Tk. {{ number_format($dealOfTheDay->price ?? 0, 0) }}</span>
                        </div>
                        <p>Handpicked for readers who want a standout title with strong reviews, premium presentation, and a smooth shopping experience.</p>
                    </div>
                    <div class="deal-pill-row">
                        <span>⚡ Limited offer</span>
                        <span>📦 In stock</span>
                        <span>🚚 Fast delivery</span>
                    </div>
                    <div class="deal-hero-actions">
                        <a href="{{ route('books.show', $dealOfTheDay->book_id) }}" class="btn btn-dark">Grab This Book</a>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-light">Explore More</a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4 h-100">
            <div class="feature-card author-panel h-100">
                <h5>Popular Authors</h5>
                <p>Explore writers readers love most.</p>
                <div class="author-list mt-3">
                    @forelse($popularAuthors->take(4) as $author)
                    <div class="author-item">
                        <div>
                            <div class="author-name">{{ $author->author_name }}</div>
                            <div class="author-meta">{{ $author->total_books }} books • {{ $author->country ?? 'Global' }}</div>
                        </div>
                        <a href="{{ route('books.index') }}?search={{ urlencode($author->author_name) }}" class="btn btn-sm btn-outline-dark">View</a>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No author highlights yet.</p>
                    @endforelse
                </div>
                <div class="author-panel-footer">
                    <span class="author-footer-pill">Curated picks</span>
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-dark">Browse all</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Releases Section -->
@if($newArrivals->count() > 0)
<div class="container section-spacing">
    <h2 class="section-title reveal-on-scroll">✨ New <span>Releases</span></h2>
    <p class="section-subtitle">Fresh arrivals and newly added titles</p>

    <div class="row g-4 reveal-on-scroll">
        @foreach($newArrivals->take(4) as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover overflow-hidden">
                    @if($book->has_cover)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; position: absolute; inset: 0;" onerror="this.onerror=null;this.style.display='none'">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);">
                            📖
                        </div>
                    @endif
                    <span class="book-badge">New</span>
                </div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author_name ?? 'Unknown' }}</p>
                    <p class="book-stock mb-2">In Stock</p>
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
@endif

<!-- Featured Books Section -->
<div class="container mt-5 section-spacing">
    <h2 class="section-title reveal-on-scroll">⭐ Featured <span>Books</span></h2>
    <p class="section-subtitle">Handpicked bestsellers and highest-rated books</p>

    <div class="row g-4 reveal-on-scroll">
        @forelse($featuredBooks as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover overflow-hidden" style="position: relative;">
                    <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); position: absolute; inset: 0;">
                        📖
                    </div>
                    @if($book->has_cover)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; position: absolute; inset: 0;" onerror="this.onerror=null;this.style.display='none'">
                    @endif
                    <span class="book-badge">Featured</span>
                </div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating ?? 0 }}
                        <small class="text-muted">({{ $book->review_count ?? 0 }})</small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="book-price">Tk. {{ number_format($book->price, 0) }}</span>
                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-dark">View</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-4">
            <p class="text-muted">No featured books available yet.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Categories Section -->
<div class="container section-spacing">
    <h2 class="section-title reveal-on-scroll">📚 Browse by <span>Category</span></h2>
    <p class="section-subtitle">Explore books organized by category</p>

    <div class="row g-4 reveal-on-scroll">
        @forelse($categorySummary as $cat)
        <div class="col-md-3">
            <a href="{{ route('categories.show', $cat->category_id) }}" class="text-decoration-none">
                <div class="category-card">
                    <div class="category-icon">{{ $cat->icon }}</div>
                    <h5 class="category-name">{{ $cat->category_name }}</h5>
                    <p class="category-count">{{ $cat->total_books }} books</p>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">No categories available.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Best Sellers Section -->
@if($bestSellers->count() > 0)
<div class="container section-spacing">
    <h2 class="section-title reveal-on-scroll">🔥 Best <span>Sellers</span></h2>
    <p class="section-subtitle">Most popular books this month</p>

    <div class="row g-4 reveal-on-scroll">
        @foreach($bestSellers->take(8) as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover overflow-hidden" style="position: relative;">
                    <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); position: absolute; inset: 0;">
                        📖
                    </div>
                    @if($book->has_cover)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; position: absolute; inset: 0;" onerror="this.onerror=null;this.style.display='none'">
                    @endif
                    <span class="book-badge">Bestseller</span>
                </div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating ?? 0 }}</p>
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
@endif

<!-- New Arrivals Section -->
@if($newArrivals->count() > 0)
<div class="container section-spacing">
    <h2 class="section-title reveal-on-scroll">✨ New <span>Arrivals</span></h2>
    <p class="section-subtitle">Recently added to our collection</p>

    <div class="row g-4 reveal-on-scroll">
        @foreach($newArrivals->take(8) as $book)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover overflow-hidden" style="position: relative;">
                    <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size: 3rem; color: #6b7280; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); position: absolute; inset: 0;">
                        📖
                    </div>
                    @if($book->has_cover)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; position: absolute; inset: 0;" onerror="this.onerror=null;this.style.display='none'">
                    @endif
                    <span class="book-badge">Popular</span>
                </div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating ?? 0 }}</p>
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
@endif

<!-- Trust / CTA Section -->
<div class="container section-spacing">
    <div class="trust-banner reveal-on-scroll">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="promo-badge">Trusted by readers</div>
                <h3>Build your personal library with books you’ll actually love</h3>
                <p>From bestsellers to hidden gems, TurnPage helps you discover, compare, and buy books in a smooth experience.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('books.index') }}" class="btn btn-dark">Explore Books</a>
            </div>
        </div>
    </div>
</div>

<!-- Top Rated Books Section -->
@if($topRatedBooks->count() > 0)
<div class="container section-spacing">
    <h2 class="section-title reveal-on-scroll">👑 Top <span>Rated</span></h2>
    <p class="section-subtitle">Highest-rated books by customers (minimum 3 reviews)</p>

    <div class="row g-4 reveal-on-scroll">
        @foreach($topRatedBooks->take(6) as $book)
        <div class="col-md-4">
            <div class="book-card">
                <div class="book-cover">
                    <span class="book-badge">Top Rated</span>
                    📖
                </div>
                <div class="p-3">
                    <p class="book-title">{{ $book->title }}</p>
                    <p class="book-author mb-1">{{ $book->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-1">★ {{ $book->star_rating ?? 0 }}
                        <small class="text-muted">({{ $book->review_count ?? 0 }})</small>
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
@endif

@endsection