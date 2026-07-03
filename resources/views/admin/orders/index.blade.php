<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .order-card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .badge-Pending { background-color: #f39c12; }
        .badge-Processing { background-color: #3498db; }
        .badge-Shipped { background-color: #9b59b6; }
        .badge-Delivered { background-color: #27ae60; }
        .badge-Cancelled { background-color: #e74c3c; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.books.index') }}">⚙️ TurnPage Admin</a>
            <div class="ms-auto">
                <a class="nav-link d-inline" href="{{ route('admin.books.index') }}">Books</a>
                <a class="nav-link d-inline" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="nav-link d-inline" href="{{ route('admin.vouchers.index') }}">Vouchers</a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">Back to Site</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Orders</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
        <div class="order-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6>Order #{{ $order->order_id }} — {{ $order->user->name ?? 'Unknown Customer' }}</h6>
                    <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}</p>
                </div>
                <span class="badge badge-{{ $order->status }} text-white">{{ $order->status }}</span>
            </div>

            <hr>

            @foreach($order->items as $item)
            <div class="d-flex justify-content-between">
                <span>{{ $item->book->title ?? 'Book' }} × {{ $item->quantity }}</span>
                <span>Tk. {{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between fw-bold mb-2">
                <span>Total</span>
                <span>Tk. {{ number_format($order->total_amount, 2) }}</span>
            </div>

            <p class="text-muted small mb-3">
                Payment: {{ $order->payment->payment_method ?? 'N/A' }} ({{ $order->payment->payment_status ?? 'N/A' }})
            </p>

            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST" class="d-flex gap-2">
                @csrf
                @method('PUT')
                <select name="status" class="form-select form-select-sm" style="max-width: 200px;">
                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-sm btn-dark">Update Status</button>
            </form>
        </div>
        @empty
        <p class="text-muted">No orders yet.</p>
        @endforelse
    </div>

</body>
</html>