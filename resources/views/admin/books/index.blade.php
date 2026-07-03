<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Manage Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .table-card { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .nav-link:hover { color: #f39c12 !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.books.index') }}">⚙️ TurnPage Admin</a>
            <a class="nav-link d-inline" href="{{ route('admin.orders.index') }}" style="color: #ecf0f1;">Orders</a>
            <a class="nav-link d-inline" href="{{ route('admin.vouchers.index') }}">Vouchers</a>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">Back to Site</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Books</h2>
            <a href="{{ route('admin.books.create') }}" class="btn btn-dark">+ Add New Book</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr>
                        <td>{{ $book->book_id }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->author_name ?? 'N/A' }}</td>
                        <td>Tk. {{ number_format($book->price, 2) }}</td>
                        <td>
                            @if($book->stock_quantity <= 5)
                                <span class="badge bg-danger">{{ $book->stock_quantity }}</span>
                            @else
                                {{ $book->stock_quantity }}
                            @endif
                        </td>
                        <td>★ {{ $book->star_rating }}</td>
                        <td>
                            <a href="{{ route('admin.books.edit', $book->book_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book->book_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>