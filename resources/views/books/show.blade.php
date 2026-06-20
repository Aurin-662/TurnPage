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
            </div>
        </div>
    </div>

</body>
</html>