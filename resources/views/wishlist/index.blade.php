<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist — TurnPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .book-cover { height: 200px; background: #ddd; display: flex; align-items: center; justify-content: center; color: #888; }
        .price-tag { color: #c0392b; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
            <div class="ms-auto">
                <a class="nav-link d-inline" href="{{ route('home') }}">Home</a>
                <a class="nav-link d-inline" href="{{ route('books.index') }}">All Books</a>
                <a class="nav-link d-inline" href="{{ route('cart.view') }}">🛒 Cart</a>
                <a class="nav-link d-inline" href="{{ route('wishlist.view') }}">❤️ Wishlist</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">My Wishlist</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            @forelse($wishlistItems as $item)
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="book-cover">No Image</div>
                    <div class="card-body">
                        <h6>{{ $item->book->title ?? 'Unknown' }}</h6>
                        <p class="text-muted small mb-1">by {{ $item->book->author->author_name ?? 'Unknown' }}</p>
                        <p class="price-tag">Tk. {{ number_format($item->book->price ?? 0, 2) }}</p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('books.show', $item->book_id) }}" class="btn btn-sm btn-outline-dark flex-grow-1">View</a>
                            <form action="{{ route('wishlist.remove', $item->wishlist_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h5 class="text-muted">Your wishlist is empty.</h5>
                <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Browse Books</a>
            </div>
            @endforelse
        </div>
    </div>

</body>
</html>