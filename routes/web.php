<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\PublisherController as AdminPublisherController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/cart/add/{bookId}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/update/{cartItemId}', [CartController::class, 'updateQuantity'])->name('cart.update');


Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');


Route::post('/books/{bookId}/review', [ReviewController::class, 'store'])->name('review.store');


Route::post('/wishlist/add/{bookId}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'view'])->name('wishlist.view');
Route::delete('/wishlist/remove/{wishlistId}', [WishlistController::class, 'remove'])->name('wishlist.remove');


Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('/books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('/books/{id}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{id}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [AdminBookController::class, 'destroy'])->name('books.destroy');

    Route::post('/authors/quick-add', [AdminAuthorController::class, 'quickAdd'])->name('authors.quickadd');
    Route::post('/publishers/quick-add', [AdminPublisherController::class, 'quickAdd'])->name('publishers.quickadd');
});


