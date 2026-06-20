<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Add book to cart
    public function add(Request $request, $bookId)
    {
        // Must be logged in
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to add items to cart.');
        }

        $userId = Session::get('user_id');
        $book = Book::findOrFail($bookId);

        // Check stock
        if ($book->stock_quantity <= 0) {
            return back()->with('error', 'Sorry, this book is out of stock.');
        }

        DB::transaction(function () use ($userId, $book) {
            // Find or create cart for this user
            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) {
                $newCartId = (Cart::max('cart_id') ?? 0) + 1;
                $cart = Cart::create([
                    'cart_id' => $newCartId,
                    'user_id' => $userId,
                    'created_at' => now(),
                ]);
            }

            // Check if book already in cart
            $cartItem = CartItem::where('cart_id', $cart->cart_id)
                                 ->where('book_id', $book->book_id)
                                 ->first();

            if ($cartItem) {
                // Increase quantity
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                // Add new item
                $newItemId = (CartItem::max('cart_item_id') ?? 0) + 1;
                CartItem::create([
                    'cart_item_id' => $newItemId,
                    'cart_id' => $cart->cart_id,
                    'book_id' => $book->book_id,
                    'quantity' => 1,
                    'price' => $book->price,
                ]);
            }
        });

        return back()->with('success', 'Book added to cart!');
    }

    // View cart
    public function view()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $cart = Cart::where('user_id', $userId)->first();

        $cartItems = [];
        $total = 0;

        if ($cart) {
            $cartItems = CartItem::with('book')->where('cart_id', $cart->cart_id)->get();
            $total = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Remove item from cart
    public function remove($cartItemId)
    {
        CartItem::where('cart_item_id', $cartItemId)->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    // Update quantity
    public function updateQuantity(Request $request, $cartItemId)
    {
        $item = CartItem::findOrFail($cartItemId);
        $item->quantity = max(1, (int) $request->quantity);
        $item->save();

        return back()->with('success', 'Cart updated.');
    }
}