<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Show checkout page
    public function show()
{
    if (!Session::has('user_id')) {
        return redirect()->route('login');
    }

    $userId = Session::get('user_id');
    $cart = Cart::where('user_id', $userId)->first();

    if (!$cart) {
        return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
    }

    $cartItems = CartItem::with('book')->where('cart_id', $cart->cart_id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
    }

    $subtotal = $cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    $discount = 0;
    $appliedVoucher = null;

    if (Session::has('applied_voucher_code')) {
        $voucher = Voucher::where('voucher_code', Session::get('applied_voucher_code'))->first();
        if ($voucher && $subtotal >= $voucher->minimum_amount) {
            $finalPrice = $this->calculateDiscountedPrice($subtotal, $voucher->discount_percent);
            $discount = round(max($subtotal - $finalPrice, 0), 2);
            $appliedVoucher = $voucher;
        }
    }

    $total = $subtotal - $discount;

    return view('checkout.index', compact('cartItems', 'subtotal', 'discount', 'total', 'appliedVoucher'));
}

// Apply a voucher code
public function applyVoucher(Request $request)
{
    $request->validate(['voucher_code' => 'required|string']);

    $voucher = Voucher::where('voucher_code', $request->voucher_code)
                       ->where('is_active', 1)
                       ->first();

    if (!$voucher) {
        return back()->with('error', 'Invalid voucher code.');
    }

    $today = now()->format('Y-m-d');
    if ($voucher->valid_from && $today < $voucher->valid_from) {
        return back()->with('error', 'This voucher is not active yet.');
    }
    if ($voucher->valid_to && $today > $voucher->valid_to) {
        return back()->with('error', 'This voucher has expired.');
    }

    Session::put('applied_voucher_code', $voucher->voucher_code);

    return back()->with('success', 'Voucher "' . $voucher->voucher_code . '" applied!');
}

// Remove applied voucher
public function removeVoucher()
{
    Session::forget('applied_voucher_code');
    return back()->with('success', 'Voucher removed.');
}


   // Process the order — calls Oracle PL/SQL Procedure
public function placeOrder(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:Credit Card,Cash on Delivery,bKash',
    ]);

    if (!Session::has('user_id')) {
        return redirect()->route('login');
    }

    $userId = Session::get('user_id');

    try {
        DB::statement('BEGIN PLACE_ORDER(:user_id, :payment_method); END;', [
            'user_id' => $userId,
            'payment_method' => $request->payment_method,
        ]);

        // If a voucher was applied, attach it to the newly created order
        if (Session::has('applied_voucher_code')) {
            $voucher = Voucher::where('voucher_code', Session::get('applied_voucher_code'))->first();

            if ($voucher) {
                $latestOrder = Order::where('user_id', $userId)->orderBy('order_id', 'desc')->first();

                if ($latestOrder) {
                    $newTotal = $this->calculateDiscountedPrice($latestOrder->total_amount, $voucher->discount_percent);
                    $discount = round(max($latestOrder->total_amount - $newTotal, 0), 2);

                    $latestOrder->voucher_id = $voucher->voucher_id;
                    $latestOrder->total_amount = $newTotal;
                    $latestOrder->save();

                    // Reflect the new total in the payment record too
                    $payment = $latestOrder->payment;
                    if ($payment) {
                        $payment->amount = $newTotal;
                        $payment->save();
                    }
                }
            }

            Session::forget('applied_voucher_code');
        }

    } catch (\Exception $e) {
        return redirect()->route('cart.view')->with('error', 'Order failed: ' . $e->getMessage());
    }

    return redirect()->route('orders.history')->with('success', 'Order placed successfully!');
}

    // Order history
    public function history()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $orders = Order::with('items.book', 'payment')
                        ->where('user_id', $userId)
                        ->orderBy('order_date', 'desc')
                        ->get();

        return view('checkout.history', compact('orders'));
    }

    private function calculateDiscountedPrice($subtotal, $discountPercent)
    {
        try {
            if (DB::getDriverName() === 'oracle') {
                $result = DB::select('SELECT GET_DISCOUNT_PRICE(?, ?) AS final_price FROM dual', [$subtotal, $discountPercent]);
                if (!empty($result) && isset($result[0]->FINAL_PRICE)) {
                    return (float) $result[0]->FINAL_PRICE;
                }
            }
        } catch (\Throwable $e) {
            // Fall back to PHP calculation when the Oracle function is unavailable.
        }

        return round($subtotal * (1 - ($discountPercent / 100)), 2);
    }
}