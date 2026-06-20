<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TurnPage — Your Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; font-family: 'Segoe UI', sans-serif; }

        .navbar { background-color: #2c3e50; padding: 1rem 0; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.6rem; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .nav-link:hover { color: #f39c12 !important; }

        .hero {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }
        .hero h1 { font-size: 3rem; font-weight: bold; }
        .hero p { font-size: 1.2rem; color: #bdc3c7; margin-bottom: 30px; }
        .hero .btn-primary {
            background-color: #c0392b;
            border: none;
            padding: 12px 35px;
            font-size: 1.1rem;
            border-radius: 30px;
        }
        .hero .btn-primary:hover { background-color: #a93226; }

        .section-title {
            text-align: center;
            margin: 60px 0 40px;
            font-weight: bold;
            color: #2c3e50;
        }

        .book-card { transition: transform 0.2s; height: 100%; border: none; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .book-cover { height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999; }
        .price-tag { color: #c0392b; font-weight: bold; }
        .star-rating { color: #f39c12; }

        footer {
            background-color: #2c3e50;
            color: #bdc3c7;
            text-align: center;
            padding: 25px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
            <div class="ms-auto">
                <a class="nav-link d-inline" href="{{ route('home') }}">Home</a>
                <a class="nav-link d-inline" href="{{ route('books.index') }}">All Books</a>
                <a class="nav-link d-inline" href="#">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1>Discover Your Next Favorite Book</h1>
            <p>Thousands of titles, one click away — TurnPage brings the bookstore to you.</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary">Browse All Books</a>
        </div>
    </div>

    <!-- Featured Books -->
    <div class="container">
        <h2 class="section-title">⭐ Featured Books</h2>

        <div class="row g-4">
            @foreach($featuredBooks as $book)
            <div class="col-md-3">
                <div class="card book-card shadow-sm">
                    <div class="book-cover">No Image</div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $book->title }}</h6>
                        <p class="text-muted small mb-1">by {{ $book->author->author_name ?? 'Unknown' }}</p>
                        <p class="mb-1"><span class="star-rating">★ {{ $book->star_rating }}</span></p>
                        <p class="price-tag mb-2">Tk. {{ number_format($book->price, 2) }}</p>
                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-dark w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p class="mb-0">&copy; 2026 TurnPage. A Database Systems Lab Project — CSE 3109/3110.</p>
    </footer>

</body>
</html>