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

    // Process the order — wrapped in a DB transaction
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:Credit Card,Cash on Delivery,bKash',
        ]);

        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::where('cart_id', $cart->cart_id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        // Check stock availability before starting transaction
        foreach ($cartItems as $item) {
            $book = Book::find($item->book_id);
            if (!$book || $book->stock_quantity < $item->quantity) {
                return redirect()->route('cart.view')
                    ->with('error', 'Sorry, "' . ($book->title ?? 'a book') . '" does not have enough stock.');
            }
        }

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        try {
            DB::transaction(function () use ($cartItems, $userId, $total, $request, $cart) {

                // 1. Create the Order
                $newOrderId = (Order::max('order_id') ?? 0) + 1;
                $order = Order::create([
                    'order_id' => $newOrderId,
                    'user_id' => $userId,
                    'voucher_id' => null,
                    'order_date' => now(),
                    'total_amount' => $total,
                    'status' => 'Pending',
                ]);

                // 2. Create Order Items + reduce stock
                $orderItemId = (OrderItem::max('order_item_id') ?? 0) + 1;
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_item_id' => $orderItemId,
                        'order_id' => $order->order_id,
                        'book_id' => $item->book_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                    $orderItemId++;

                    // Reduce stock quantity
                    $book = Book::find($item->book_id);
                    $book->stock_quantity -= $item->quantity;
                    $book->save();
                }

                // 3. Create Payment record
                $newPaymentId = (Payment::max('payment_id') ?? 0) + 1;
                Payment::create([
                    'payment_id' => $newPaymentId,
                    'order_id' => $order->order_id,
                    'payment_method' => $request->payment_method,
                    'amount' => $total,
                    'payment_status' => $request->payment_method === 'Cash on Delivery' ? 'Pending' : 'Completed',
                    'payment_date' => now(),
                    'transaction_id' => 'TXN' . time(),
                ]);

                // 4. Clear the cart
                CartItem::where('cart_id', $cart->cart_id)->delete();
            });
        } catch (\Exception $e) {
            // If anything fails, the whole transaction rolls back automatically
            return redirect()->route('cart.view')->with('error', 'Order failed. Please try again.');
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