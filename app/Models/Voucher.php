<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'VOUCHER';
    protected $primaryKey = 'voucher_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'voucher_id', 'voucher_code', 'discount_percent', 'valid_from',
        'valid_to', 'minimum_amount', 'usage_limit', 'is_active'
    ];
}