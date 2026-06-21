<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'PAYMENT';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'payment_id', 'order_id', 'payment_method', 'amount',
        'payment_status', 'payment_date', 'transaction_id'
    ];
}