@extends('layouts.admin')

@section('title', 'Manage Books — Admin')

@section('styles')
<style>
    .table-card { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .stock-low { background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 style="font-family:'Inter',sans-serif; font-weight:700;">Manage Books</h2>
            <p class="text-muted mb-0">Search by book title, ISBN, author, or publisher.</p>
        </div>
        <div class="d-flex gap-2 w-100 w-md-auto">
            <form action="{{ route('admin.books.index') }}" method="GET" class="d-flex gap-2 w-100">
                <input type="search" name="search" class="form-control form-control-sm" placeholder="Search books..." value="{{ $search ?? request('search') }}">
                <button type="submit" class="btn btn-dark btn-sm">Search</button>
            </form>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn btn-dark">+ Add New Book</a>
    </div>

    <div class="table-card">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
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
                    <td class="text-muted small">{{ $book->book_id }}</td>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->author->author_name ?? 'N/A' }}</td>
                    <td>Tk. {{ number_format($book->price, 0) }}</td>
                    <td>
                        @if($book->stock_quantity <= 5)
                            <span class="stock-low">{{ $book->stock_quantity }} low</span>
                        @else
                            {{ $book->stock_quantity }}
                        @endif
                    </td>
                    <td>⭐ {{ $book->star_rating }}</td>
                    <td>
                        <a href="{{ route('admin.books.edit', $book->book_id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book->book_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
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
@endsection