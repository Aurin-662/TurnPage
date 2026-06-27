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
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .nav-link:hover { color: #f39c12 !important; }

        .filter-bar {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

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
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
            <div class="ms-auto">
                <a class="nav-link d-inline" href="{{ route('home') }}">Home</a>
                <a class="nav-link d-inline" href="{{ route('books.index') }}">All Books</a>
                <a class="nav-link d-inline" href="{{ route('cart.view') }}">🛒 Cart</a>
                <a class="nav-link d-inline" href="{{ route('wishlist.view') }}">❤️ Wishlist</a>
                @if(session('user_id'))
                    <span class="nav-link d-inline">Hi, {{ session('user_name') }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                             @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                @else
                 <a class="nav-link d-inline" href="{{ route('login') }}">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">All Books</h2>

        <!-- Search & Filter Form -->
        <div class="filter-bar">
            <form action="{{ route('books.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Title or author..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Language</label>
                    <select name="language" class="form-select">
                        <option value="">All</option>
                        <option value="Bangla" {{ request('language') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                        <option value="English" {{ request('language') == 'English' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Min Price</label>
                    <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Max Price</label>
                    <input type="number" name="max_price" class="form-control" placeholder="1000" value="{{ request('max_price') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="">Default</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Apply Filters</button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <p class="text-muted">{{ $books->count() }} book(s) found</p>

        <div class="row g-4">
            @forelse($books as $book)
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
            @empty
            <div class="col-12 text-center text-muted py-5">
                <h5>No books found matching your criteria.</h5>
            </div>
            @endforelse
        </div>
    </div>

</body>
</html>