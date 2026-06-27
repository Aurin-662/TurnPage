<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $book->title }} — TurnPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .book-cover-large { height: 350px; background: #ddd; display: flex; align-items: center; justify-content: center; color: #888; }
        .price-tag { color: #c0392b; font-weight: bold; font-size: 1.6rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('books.index') }}">📖 TurnPage</a>
        </div>
    </nav>

    <div class="container mt-5">
        <a href="{{ route('books.index') }}" class="btn btn-link mb-3">&larr; Back to all books</a>

        <div class="row">
            <div class="col-md-4">
                <div class="book-cover-large">No Image</div>
            </div>
            <div class="col-md-8">
                <h2>{{ $book->title }}</h2>
                <p class="text-muted">by <strong>{{ $book->author->author_name ?? 'Unknown' }}</strong></p>
                <p>Publisher: {{ $book->publisher->publisher_name ?? 'Unknown' }}</p>
                <p class="star-rating">★ {{ $book->star_rating }} ({{ $book->review_count }} reviews)</p>
                <p class="price-tag">Tk. {{ number_format($book->price, 2) }}</p>
                <p>Stock: {{ $book->stock_quantity }} copies available</p>
                <p>Language: {{ $book->language }}</p>
                <p>Pages: {{ $book->page_count }}</p>
                <p>ISBN: {{ $book->isbn }}</p>

                <form action="{{ route('cart.add', $book->book_id) }}" method="POST">
                                @csrf
                        <button type="submit" class="btn btn-danger mt-3">Add to Cart</button>
                </form>

                <form action="{{ route('wishlist.add', $book->book_id) }}" method="POST" class="d-inline">
                       @csrf
                        <button type="submit" class="btn btn-outline-danger mt-3">❤️ Add to Wishlist</button>
                </form>

                <hr class="mt-5">

                 <h4 class="mt-4">Customer Reviews</h4>

                   @if(session('success'))
                   <div class="alert alert-success">{{ session('success') }}</div>
                  @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

        <!-- Write a Review Form -->
        <div class="card p-4 mb-4 mt-3">
            <h6>Write a Review</h6>
            <form action="{{ route('review.store', $book->book_id) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select" style="max-width: 150px;" required>
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Good</option>
                        <option value="3">3 - Average</option>
                        <option value="2">2 - Poor</option>
                        <option value="1">1 - Terrible</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Your Review</label>
                    <textarea name="review_text" class="form-control" rows="3" placeholder="Share your thoughts about this book..."></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Submit Review</button>
            </form>
        </div>

        <!-- All Reviews -->
        @forelse($reviews as $review)
        <div class="border-bottom pb-3 mb-3">
            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
            <span class="star-rating">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $review->rating ? '★' : '☆' }}
                @endfor
            </span>
            <p class="mb-1 text-muted small">{{ \Carbon\Carbon::parse($review->review_date)->format('d M Y') }}</p>
            <p>{{ $review->review_text }}</p>
        </div>
        @empty
        <p class="text-muted">No reviews yet. Be the first to review this book!</p>
        @endforelse

            </div>
        </div>
    </div>

</body>
</html>