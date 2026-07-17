@extends('layouts.app')

@section('title', 'My Orders — TurnPage')

@section('styles')
<style>
    .order-card { background: #fff; border-radius: 10px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .status-Pending { background: #fff3cd; color: #856404; }
    .status-Processing { background: #cfe2ff; color: #084298; }
    .status-Shipped { background: #e2d9f3; color: #432874; }
    .status-Delivered { background: #d1e7dd; color: #0a3622; }
    .status-Cancelled { background: #f8d7da; color: #58151c; }
    .price-tag { color: #c0392b; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-family:'Merriweather',serif;">My Orders</h2>

    @forelse($orders as $order)
    <div class="order-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-0">Order #{{ $order->order_id }}</h6>
                <small class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</small>
            </div>
            <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
        </div>
        <hr>
        @foreach($order->items as $item)
        <div class="d-flex justify-content-between mb-1">
            <span>{{ $item->book->title ?? 'Book' }} <span class="text-muted">× {{ $item->quantity }}</span></span>
            <span>Tk. {{ number_format($item->price * $item->quantity, 2) }}</span>
        </div>
        @endforeach
        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $order->payment->payment_method ?? 'N/A' }} · {{ $order->payment->payment_status ?? 'N/A' }}</small>
            <span class="price-tag">Tk. {{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <div style="font-size:5rem;">📦</div>
        <h4 class="mt-3 text-muted">No orders yet</h4>
        <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Start Shopping</a>
    </div>
    @endforelse
</div>
@endsection