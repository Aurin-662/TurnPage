<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    // Add book to wishlist
    public function add($bookId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to use wishlist.');
        }

        $userId = Session::get('user_id');

        $existing = Wishlist::where('user_id', $userId)->where('book_id', $bookId)->first();
        if ($existing) {
            return back()->with('error', 'This book is already in your wishlist.');
        }

        $newId = (Wishlist::max('wishlist_id') ?? 0) + 1;

        Wishlist::create([
            'wishlist_id' => $newId,
            'user_id' => $userId,
            'book_id' => $bookId,
            'added_at' => now(),
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    // View wishlist
    public function view()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $wishlistItems = Wishlist::with('book.author')->where('user_id', $userId)->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    // Remove from wishlist
    public function remove($wishlistId)
    {
        Wishlist::where('wishlist_id', $wishlistId)->delete();
        return back()->with('success', 'Removed from wishlist.');
    }
}