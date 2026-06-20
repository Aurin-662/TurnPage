<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart — TurnPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .cart-item-img { width: 70px; height: 90px; background: #ddd; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #888; }
        .total-box { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
            <div class="ms-auto">
                <a class="nav-link d-inline" href="{{ route('home') }}">Home</a>
                <a class="nav-link d-inline" href="{{ route('books.index') }}">All Books</a>
                <a class="nav-link d-inline" href="{{ route('cart.view') }}">🛒 Cart</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">My Cart</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach($cartItems as $item)
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="cart-item-img me-3">No Image</div>
                        <div class="flex-grow-1">
                            <h6>{{ $item->book->title ?? 'Unknown Book' }}</h6>
                            <p class="text-muted mb-1">Tk. {{ number_format($item->price, 2) }} each</p>

                            <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" class="d-inline-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                            </form>
                        </div>
                        <div class="text-end">
                            <p class="fw-bold">Tk. {{ number_format($item->price * $item->quantity, 2) }}</p>
                            <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="col-md-4">
                <div class="total-box">
                    <h5>Order Summary</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Tk. {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total</span>
                        <span>Tk. {{ number_format($total, 2) }}</span>
                    </div>
                    <button class="btn btn-danger w-100">Proceed to Checkout</button>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <h5 class="text-muted">Your cart is empty.</h5>
            <a href="{{ route('books.index') }}" class="btn btn-dark mt-3">Browse Books</a>
        </div>
        @endif
    </div>

</body>
</html>