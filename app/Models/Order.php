<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'ORDERS';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['order_id', 'user_id', 'voucher_id', 'order_date', 'total_amount', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'user_id');
}
}