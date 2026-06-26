<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'ORDER_ITEM';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['order_item_id', 'order_id', 'book_id', 'quantity', 'price'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
    
    public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}

}