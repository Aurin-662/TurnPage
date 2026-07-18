@extends('layouts.app')

@section('title', $category->category_name . ' — TurnPage')

@section('styles')
<style>
    .category-header { background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%); color: #fff; padding: 40px 0; }
    .category-header h1 { font-family: 'Merriweather', serif; font-size: 2rem; }

    .filter-sidebar { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
    .filter-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 16px; color: #1a1a2e; }
    .filter-group { margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0; }
    .filter-group:last-child { border-bottom: none; }
    .filter-item { margin-bottom: 8px; }
    .filter-item label { cursor: pointer; margin: 0; font-size: 0.95rem; }

    .book-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .book-cover { height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
    .book-title { font-weight: 600; font-size: 0.95rem; }
    .book-author { color: #888; font-size: 0.82rem; }
    .book-badge { display: inline-block; background: #e8a045; color: #fff; padding: 4px 8px; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 10px; }
    .book-price { color: #c0392b; font-weight: 700; }
    .star-rating { color: #e8a045; font-size: 0.85rem; }

    .pagination { justify-content: center; margin-top: 32px; }
    .pagination .page-link { color: #1a1a2e; border-color: #ddd; }
    .pagination .page-link:hover { color: #fff; background: #1a1a2e; border-color: #1a1a2e; }
    .pagination .page-item.active .page-link { background: #e8a045; border-color: #e8a045; }
</style>
@endsection

@section('content')

<div class="category-header">
    <div class="container">
        <h1>{{ $category->category_name }}</h1>
        <p class="mt-2 mb-0">Browse all books in this category</p>
    </div>
</div>

<div class="container mt-5 pb-5">
    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-md-3">
            <div class="filter-sidebar">
                <h5 class="filter-title">🔍 Filters</h5>

                <form method="GET" action="{{ route('categories.show', $category->category_id) }}">
                    <!-- Price Filter -->
                    <div class="filter-group">
                        <label class="filter-title">Price Range</label>
                        <div class="filter-item">
                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                        </div>
                        <div class="filter-item">
                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <!-- Author Filter -->
                    @if($authors->count() > 0)
                    <div class="filter-group">
                        <label class="filter-title">Author</label>
                        @foreach($authors as $author)
                        <div class="filter-item">
                            <label>
                                <input type="checkbox" name="author" value="{{ $author }}" {{ request('author') == $author ? 'checked' : '' }}> 
                                {{ $author }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Sort -->
                    <div class="filter-group">
                        <label class="filter-title">Sort By</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="">Relevance</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 mb-2">Apply Filters</button>
                    <a href="{{ route('categories.show', $category->category_id) }}" class="btn btn-outline-secondary w-100">Reset</a>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="col-md-9">
            <p class="text-muted mb-4">{{ $books->total() }} book(s) found</p>

            <div class="row g-4">
                @forelse($books as $book)
                <div class="col-md-4">
                    <div class="book-card">
                        <div class="book-cover">📖</div>
                        <div class="p-3">
                            <span class="book-badge">Category Pick</span>
                            <p class="book-title mb-1">{{ $book->title }}</p>
                            <p class="book-author mb-2">{{ $book->author_name ?? 'Unknown' }}</p>
                            <p class="star-rating mb-2">★ {{ $book->star_rating }}
                                <small class="text-muted">({{ $book->review_count }})</small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="book-price">Tk. {{ number_format($book->price, 0) }}</span>
                                <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-dark">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">No books found matching your filters.</h5>
                    <a href="{{ route('categories.show', $category->category_id) }}" class="btn btn-dark mt-3">Clear Filters</a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($books->total() > 12)
            <nav>
                {{ $books->links('pagination::bootstrap-4') }}
            </nav>
            @endif
        </div>
    </div>
</div>

@endsection
