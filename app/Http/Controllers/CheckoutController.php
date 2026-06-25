<?php

namespace App\Http\Controllers;

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

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
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
        // Call the Oracle PL/SQL procedure directly
        DB::statement('BEGIN PLACE_ORDER(:user_id, :payment_method); END;', [
            'user_id' => $userId,
            'payment_method' => $request->payment_method,
        ]);
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
}