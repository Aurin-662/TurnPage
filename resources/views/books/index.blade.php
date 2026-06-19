<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TurnPage — All Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .book-card { transition: transform 0.2s; height: 100%; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .book-cover { height: 220px; background: #ddd; display: flex; align-items: center; justify-content: center; color: #888; }
        .price-tag { color: #c0392b; font-weight: bold; font-size: 1.1rem; }
        .star-rating { color: #f39c12; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('books.index') }}">📖 TurnPage</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">All Books</h2>

        <div class="row g-4">
            @foreach($books as $book)
            <div class="col-md-3">
                <div class="card book-card shadow-sm">
                    <div class="book-cover">No Image</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            by {{ $book->author->author_name ?? 'Unknown' }}
                        </p>
                        <p class="card-text mb-1">
                            <span class="star-rating">★ {{ $book->star_rating }}</span>
                            <small class="text-muted">({{ $book->review_count }} reviews)</small>
                        </p>
                        <p class="price-tag">Tk. {{ number_format($book->price, 2) }}</p>
                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-dark w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>