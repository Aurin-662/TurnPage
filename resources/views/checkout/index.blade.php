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
            <!-- LEFT COLUMN: Order Items + Voucher -->
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
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Tk. {{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount ({{ $appliedVoucher->voucher_code }})</span>
                        <span>- Tk. {{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>Tk. {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="summary-box">
                    <h5>Have a Voucher?</h5>
                    <hr>

                    @if(session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger py-2">{{ session('error') }}</div>
                    @endif

                    @if($appliedVoucher)
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Applied: <strong>{{ $appliedVoucher->voucher_code }}</strong> ({{ $appliedVoucher->discount_percent }}% off)</span>
                            <form action="{{ route('checkout.removeVoucher') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('checkout.applyVoucher') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="voucher_code" class="form-control" placeholder="Enter voucher code">
                            <button type="submit" class="btn btn-dark">Apply</button>
                        </form>
                        <p class="text-muted small mt-2 mb-0">Try: WELCOME10, BOOKLOVER20, EIDOFFER</p>
                    @endif
                </div>

            </div>

            <!-- RIGHT COLUMN: Payment Method -->
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