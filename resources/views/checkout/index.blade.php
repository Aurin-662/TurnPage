@extends('layouts.app')

@section('title', 'Checkout — TurnPage')

@section('styles')
<style>
    .checkout-card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .price-tag { color: #c0392b; font-weight: 700; }
    .btn-place-order { background: #c0392b; color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 1rem; width: 100%; }
    .btn-place-order:hover { background: #a93226; color: #fff; }
    .payment-option { border: 2px solid #eee; border-radius: 8px; padding: 12px 16px; margin-bottom: 10px; cursor: pointer; transition: border-color 0.2s; }
    .payment-option:has(input:checked) { border-color: #1a1a2e; background: #f8f5f0; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-family:'Merriweather',serif;">Checkout</h2>

    <div class="row g-4">
        <div class="col-md-7">
            <!-- Order Summary -->
            <div class="checkout-card mb-4">
                <h6 class="mb-3">Order Items</h6>
                <hr>
                @foreach($cartItems as $item)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $item->book->title ?? 'Book' }} <span class="text-muted">× {{ $item->quantity }}</span></span>
                    <span>Tk. {{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Subtotal</span>
                    <span>Tk. {{ number_format($subtotal, 2) }}</span>
                </div>
                @if($discount > 0)
                <div class="d-flex justify-content-between mb-1 text-success">
                    <span>Discount ({{ $appliedVoucher->voucher_code }})</span>
                    <span>- Tk. {{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span class="price-tag">Tk. {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Voucher -->
            <div class="checkout-card">
                <h6 class="mb-3">🎫 Have a Voucher?</h6>
                @if($appliedVoucher)
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success">✅ <strong>{{ $appliedVoucher->voucher_code }}</strong> ({{ $appliedVoucher->discount_percent }}% off)</span>
                    <form action="{{ route('checkout.removeVoucher') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                </div>
                @else
                <form action="{{ route('checkout.applyVoucher') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="voucher_code" class="form-control" placeholder="Enter voucher code...">
                    <button type="submit" class="btn btn-dark">Apply</button>
                </form>
                <p class="text-muted small mt-2 mb-0">Try: WELCOME10, BOOKLOVER20</p>
                @endif
            </div>
        </div>

        <!-- Payment -->
        <div class="col-md-5">
            <div class="checkout-card">
                <h6 class="mb-3">Payment Method</h6>
                <form action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    <label class="payment-option d-flex align-items-center gap-3">
                        <input type="radio" name="payment_method" value="Cash on Delivery" checked> <span>💵 Cash on Delivery</span>
                    </label>
                    <label class="payment-option d-flex align-items-center gap-3">
                        <input type="radio" name="payment_method" value="Credit Card"> <span>💳 Credit Card</span>
                    </label>
                    <label class="payment-option d-flex align-items-center gap-3">
                        <input type="radio" name="payment_method" value="bKash"> <span>📱 bKash</span>
                    </label>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total to Pay</span>
                            <span class="price-tag fw-bold">Tk. {{ number_format($total, 2) }}</span>
                        </div>
                        <button type="submit" class="btn-place-order">Place Order →</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection