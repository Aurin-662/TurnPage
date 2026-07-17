@extends('layouts.admin')

@section('title', 'Manage Orders — Admin')

@section('styles')
<style>
    .order-card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .status-Pending { background: #fff3cd; color: #856404; }
    .status-Processing { background: #cfe2ff; color: #084298; }
    .status-Shipped { background: #e2d9f3; color: #432874; }
    .status-Delivered { background: #d1e7dd; color: #0a3622; }
    .status-Cancelled { background: #f8d7da; color: #58151c; }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-weight:700;">Manage Orders</h2>

    @forelse($orders as $order)
    <div class="order-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>Order #{{ $order->order_id }}</strong>
                <span class="text-muted small ms-2">— {{ $order->user->name ?? 'Unknown' }}</span>
                <span class="text-muted small ms-2">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}</span>
            </div>
            <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
        </div>
        <hr class="my-2">
        @foreach($order->items as $item)
        <div class="d-flex justify-content-between small">
            <span>{{ $item->book->title ?? 'Book' }} × {{ $item->quantity }}</span>
            <span>Tk. {{ number_format($item->price * $item->quantity, 0) }}</span>
        </div>
        @endforeach
        <hr class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">Tk. {{ number_format($order->total_amount, 0) }}</span>
                <span class="text-muted small ms-2">· {{ $order->payment->payment_method ?? 'N/A' }}</span>
            </div>
            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST" class="d-flex gap-2">
                @csrf
                @method('PUT')
                <select name="status" class="form-select form-select-sm" style="width:160px;">
                    @foreach(['Pending','Processing','Shipped','Delivered','Cancelled'] as $status)
                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-dark">Update</button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-muted">No orders yet.</p>
    @endforelse
</div>
@endsection