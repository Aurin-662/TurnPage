<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'WISHLIST';
    protected $primaryKey = 'wishlist_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['wishlist_id', 'user_id', 'book_id', 'added_at'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}