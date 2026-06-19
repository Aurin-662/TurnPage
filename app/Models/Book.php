<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'BOOK';
    protected $primaryKey = 'book_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'book_id', 'author_id', 'publisher_id', 'title', 'isbn',
        'price', 'stock_quantity', 'page_count', 'language',
        'image', 'star_rating', 'review_count'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'publisher_id');
    }
}