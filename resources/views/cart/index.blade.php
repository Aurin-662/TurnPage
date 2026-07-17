@extends('layouts.app')

@section('title', 'My Cart — TurnPage')

@section('styles')
<style>
    .cart-card { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 16px; }
    .cart-book-cover { width: 70px; height: 90px; background: linear-gradient(135deg, #e8e0d5, #d4c9bb); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; }
    .summary-box { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); position: sticky; top: 90px; }
    .btn-checkout { background: #c0392b; color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 1rem; width: 100%; }
    .btn-checkout:hover { background: #a93226; color: #fff; }
    .price-tag { color: #c0392b; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-family:'Merriweather',serif;">My Cart</h2>

    @if(count($cartItems) > 0)
    <div class="row g-4">
        <div class="col-md-8">
            @foreach($cartItems as $item)
            <div class="cart-card d-flex gap-3 align-items-center">
                <div class="cart-book-cover">📖</div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $item->book->title ?? 'Unknown' }}</h6>
                    <p class="text-muted small mb-2">Tk. {{ number_format($item->price, 2) }} each</p>
                    <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width:70px;">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                    </form>
                </div>
                <div class="text-end">
                    <p class="price-tag mb-2">Tk. {{ number_format($item->price * $item->quantity, 2) }}</p>
                    <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-md-4">
            <div class="summary-box">
                <h6 class="mb-3">Order Summary</h6>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal ({{ count($cartItems) }} items)</span>
                    <span>Tk. {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold mb-4">
                    <span>Total</span>
                    <span class="price-tag">Tk. {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                </div>
                <a href="{{ route('checkout.show') }}" class="btn-checkout text-decoration-none d-block text-center">Proceed to Checkout →</a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div style="font-size:5rem;">🛒</div>
        <h4 class="mt-3 text-muted">Your cart is empty</h4>
        <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Browse Books</a>
    </div>
    @endif
</div>
@endsection