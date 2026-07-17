@extends('layouts.app')

@section('title', 'My Wishlist — TurnPage')

@section('styles')
<style>
    .book-card { background: #fff; border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s; height: 100%; }
    .book-card:hover { transform: translateY(-4px); }
    .book-cover { height: 200px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
    .price-tag { color: #c0392b; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-family:'Merriweather',serif;">❤️ My Wishlist</h2>

    <div class="row g-4">
        @forelse($wishlistItems as $item)
        <div class="col-md-3">
            <div class="book-card">
                <div class="book-cover">📖</div>
                <div class="p-3">
                    <h6 class="mb-1">{{ $item->book->title ?? 'Unknown' }}</h6>
                    <p class="text-muted small mb-2">{{ $item->book->author->author_name ?? '' }}</p>
                    <p class="price-tag mb-3">Tk. {{ number_format($item->book->price ?? 0, 0) }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('books.show', $item->book_id) }}" class="btn btn-sm btn-dark flex-grow-1">View</a>
                        <form action="{{ route('wishlist.remove', $item->wishlist_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">✕</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div style="font-size:5rem;">❤️</div>
            <h4 class="mt-3 text-muted">Your wishlist is empty</h4>
            <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Browse Books</a>
        </div>
        @endforelse
    </div>
</div>
@endsection