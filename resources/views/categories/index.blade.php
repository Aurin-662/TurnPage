@extends('layouts.app')

@section('title', 'Book Categories — TurnPage')

@section('styles')
<style>
    .category-header { background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%); color: #fff; padding: 40px 0; }
    .category-header h1 { font-family: 'Merriweather', serif; font-size: 2rem; }
    .category-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-top: 32px; }
    .category-card { background: #fff; border-radius: 12px; padding: 32px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; text-decoration: none !important; color: inherit; }
    .category-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .category-icon { font-size: 3.5rem; margin-bottom: 16px; }
    .category-name { font-size: 1.3rem; font-weight: 600; margin-bottom: 12px; color: #1a1a2e; }
    .category-meta { display: flex; justify-content: space-around; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0; }
    .category-stat { text-align: center; }
    .stat-value { font-weight: 700; font-size: 1.1rem; color: #e8a045; }
    .stat-label { font-size: 0.85rem; color: #888; }
</style>
@endsection

@section('content')

<div class="category-header">
    <div class="container">
        <h1>📚 Browse by Category</h1>
        <p class="mt-2 mb-0">Explore our collection of books organized by category</p>
    </div>
</div>

<div class="container mt-5 pb-5">
    <div class="category-grid">
        @forelse($categories as $cat)
        <a href="{{ route('categories.show', $cat->category_id) }}" class="category-card">
            <div class="category-icon">{{ $cat->icon }}</div>
            <h3 class="category-name">{{ $cat->category_name }}</h3>
            <div class="category-meta">
                <div class="category-stat">
                    <div class="stat-value">{{ $cat->total_books }}</div>
                    <div class="stat-label">Books</div>
                </div>
                <div class="category-stat">
                    <div class="stat-value">Tk. {{ number_format($cat->avg_price, 0) }}</div>
                    <div class="stat-label">Avg Price</div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No categories available yet.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
