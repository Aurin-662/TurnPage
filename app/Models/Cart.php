<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'CART';
    protected $primaryKey = 'cart_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['cart_id', 'user_id', 'created_at'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}