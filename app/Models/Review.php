<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'REVIEW';
    protected $primaryKey = 'review_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['review_id', 'user_id', 'book_id', 'rating', 'review_text', 'review_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}