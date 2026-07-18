@extends('layouts.app')

@section('title', $book->title . ' — TurnPage')

@section('styles')
<style>
    .book-cover-large {
        height: 380px;
        background: linear-gradient(135deg, #e8e0d5, #d4c9bb);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .book-title { font-family: 'Merriweather', serif; font-size: 1.8rem; font-weight: 700; }
    .book-author { color: #666; font-size: 1rem; }
    .book-price { color: #c0392b; font-weight: 700; font-size: 1.8rem; }
    .star-rating { color: #e8a045; font-size: 1.1rem; }
    .meta-badge { background: #f0f0f0; border-radius: 6px; padding: 4px 12px; font-size: 0.85rem; color: #555; display: inline-block; margin-right: 8px; margin-bottom: 8px; }
    .btn-cart { background: #c0392b; color: #fff; border: none; padding: 12px 32px; border-radius: 8px; font-size: 1rem; }
    .btn-cart:hover { background: #a93226; color: #fff; }
    .btn-wishlist { border: 2px solid #e8a045; color: #e8a045; background: #fff; padding: 12px 24px; border-radius: 8px; font-size: 1rem; }
    .btn-wishlist:hover { background: #e8a045; color: #fff; }
    .review-card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .review-form { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; }
    .section-divider { border: none; border-top: 2px solid #efe8df; margin: 40px 0; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">

    <a href="{{ route('books.index') }}" class="text-muted text-decoration-none mb-3 d-inline-block">← Back to all books</a>

    <div class="row g-5 mt-1 reveal-on-scroll">
        <!-- Book Cover -->
        <div class="col-md-4">
            <div class="book-cover-large">📖</div>
        </div>

        <!-- Book Details -->
        <div class="col-md-8">
            <h1 class="book-title mb-2">{{ $book->title }}</h1>
            <p class="book-author mb-3">by <strong>{{ $book->author->author_name ?? 'Unknown' }}</strong></p>

            <p class="mb-3">
                <span class="star-rating">{{ str_repeat('★', (int)$book->star_rating) }}{{ str_repeat('☆', 5 - (int)$book->star_rating) }}</span>
                <span class="text-muted ms-1">{{ $book->star_rating }} ({{ $book->review_count }} reviews)</span>
            </p>

            <p class="book-price mb-3">Tk. {{ number_format($book->price, 2) }}</p>

            <div class="mb-4">
                <span class="meta-badge">📚 {{ $book->page_count }} pages</span>
                <span class="meta-badge">🌐 {{ $book->language }}</span>
                <span class="meta-badge">🏢 {{ $book->publisher->publisher_name ?? 'Unknown' }}</span>
                @if($book->stock_quantity > 0)
                    <span class="meta-badge" style="background:#d4edda;color:#155724;">✅ In Stock ({{ $book->stock_quantity }})</span>
                @else
                    <span class="meta-badge" style="background:#f8d7da;color:#721c24;">❌ Out of Stock</span>
                @endif
            </div>

            @if($book->isbn)
            <p class="text-muted small mb-4">ISBN: {{ $book->isbn }}</p>
            @endif

            <div class="d-flex gap-3 flex-wrap">
                @if($book->stock_quantity > 0)
                <form action="{{ route('cart.add', $book->book_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cart">🛒 Add to Cart</button>
                </form>
                @endif

                <form action="{{ route('wishlist.add', $book->book_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-wishlist">❤️ Wishlist</button>
                </form>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Related Products Section -->
    <h3 class="mb-4" style="font-family:'Merriweather',serif;">You Might Also Like</h3>
    
    @if($relatedBooks && $relatedBooks->count() > 0)
    <div class="row g-4 mb-5">
        @foreach($relatedBooks as $relBook)
        <div class="col-md-3">
            <div class="book-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s; height: 100%;">
                <div class="book-cover" style="height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem;">📖</div>
                <div class="p-3">
                    <p class="book-title mb-1">{{ $relBook->title }}</p>
                    <p class="book-author mb-2" style="color: #888; font-size: 0.82rem;">{{ $relBook->author->author_name ?? 'Unknown' }}</p>
                    <p class="star-rating mb-2" style="color: #e8a045; font-size: 0.85rem;">★ {{ $relBook->star_rating }}
                        <small class="text-muted">({{ $relBook->review_count }})</small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="book-price" style="color: #c0392b; font-weight: 700;">Tk. {{ number_format($relBook->price, 0) }}</span>
                        <a href="{{ route('books.show', $relBook->book_id) }}" class="btn btn-sm btn-outline-dark">View</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-muted mb-5">No related books available.</p>
    @endif

    <hr class="section-divider">

    <!-- Review Section -->
    <h3 class="mb-4" style="font-family:'Merriweather',serif;">Customer Reviews</h3>

    <!-- Write Review Form -->
    @if(session('user_id'))
    <div class="review-form reveal-on-scroll">
        <h6 class="mb-3">Write a Review</h6>
        <form action="{{ route('review.store', $book->book_id) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="5">★★★★★ Excellent</option>
                        <option value="4">★★★★☆ Good</option>
                        <option value="3">★★★☆☆ Average</option>
                        <option value="2">★★☆☆☆ Poor</option>
                        <option value="1">★☆☆☆☆ Terrible</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label small">Your Review</label>
                    <textarea name="review_text" class="form-control" rows="2" placeholder="Share your thoughts..."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-dark mt-3">Submit Review</button>
        </form>
    </div>
    @else
    <div class="alert alert-light border mb-4">
        <a href="{{ route('login') }}">Login</a> to write a review.
    </div>
    @endif

    <!-- Reviews List -->
    @forelse($reviews as $review)
    <div class="review-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
            <span class="star-rating">
                {{ str_repeat('★', (int)$review->rating) }}{{ str_repeat('☆', 5 - (int)$review->rating) }}
            </span>
        </div>
        <p class="text-muted small mb-2">{{ \Carbon\Carbon::parse($review->review_date)->format('d M Y') }}</p>
        <p class="mb-0">{{ $review->review_text }}</p>
    </div>
    @empty
    <p class="text-muted">No reviews yet. Be the first!</p>
    @endforelse

</div>
@endsection