<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders — TurnPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .order-card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .badge-status { font-size: 0.8rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
            <div class="ms-auto">
                <a class="nav-link d-inline text-white" href="{{ route('books.index') }}">All Books</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">My Orders</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
        <div class="order-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6>Order #{{ $order->order_id }} — {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</h6>
                <span class="badge bg-secondary badge-status">{{ $order->status }}</span>
            </div>
            <hr>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between">
                <span>{{ $item->book->title ?? 'Book' }} × {{ $item->quantity }}</span>
                <span>Tk. {{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span>Tk. {{ number_format($order->total_amount, 2) }}</span>
            </div>
            <p class="text-muted small mt-2 mb-0">
                Payment: {{ $order->payment->payment_method ?? 'N/A' }} ({{ $order->payment->payment_status ?? 'N/A' }})
            </p>
        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-muted">No orders yet.</h5>
            <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Start Shopping</a>
        </div>
        @endforelse
    </div>

</body>
</html>