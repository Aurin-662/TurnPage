<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    public function store(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:500',
        ]);

        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to write a review.');
        }

        $userId = Session::get('user_id');

        // Check if user already reviewed this book
        $existing = Review::where('user_id', $userId)->where('book_id', $bookId)->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this book.');
        }

        //  Verify the user has actually purchased this book
        $hasPurchased = OrderItem::where('book_id', $bookId)
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You can only review books you have purchased.');
        }

        $newReviewId = (Review::max('review_id') ?? 0) + 1;

        Review::create([
            'review_id' => $newReviewId,
            'user_id' => $userId,
            'book_id' => $bookId,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'review_date' => now(),
        ]);

        // The Oracle COMPOUND TRIGGER automatically updates BOOK.star_rating and review_count

        return back()->with('success', 'Thank you for your review!');
    }
}