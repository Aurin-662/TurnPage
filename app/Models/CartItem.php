<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'CART_ITEM';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['cart_item_id', 'cart_id', 'book_id', 'quantity', 'price'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }
}