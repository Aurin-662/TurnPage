<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout — TurnPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .navbar { background-color: #2c3e50; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .summary-box { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 TurnPage</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Checkout</h2>

        <div class="row">
            <div class="col-md-7">
                <div class="summary-box mb-4">
                    <h5>Order Items</h5>
                    <hr>
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item->book->title ?? 'Book' }} × {{ $item->quantity }}</span>
                        <span>Tk. {{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>Tk. {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="summary-box">
                    <h5>Payment Method</h5>
                    <hr>
                    <form action="{{ route('checkout.place') }}" method="POST">
                        @csrf
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="Cash on Delivery" id="cod" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="Credit Card" id="cc">
                            <label class="form-check-label" for="cc">Credit Card</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="bKash" id="bkash">
                            <label class="form-check-label" for="bkash">bKash</label>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>