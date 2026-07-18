<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

    protected $appends = ['cover_url', 'has_cover'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'publisher_id');
    }

    public function getCoverUrlAttribute()
    {
        if (!empty($this->image)) {
            if (Str::startsWith($this->image, ['http://', 'https://'])) {
                return $this->image;
            }

            return asset($this->image);
        }

        if (!empty($this->isbn)) {
            return "https://covers.openlibrary.org/b/isbn/{$this->isbn}-L.jpg?default=false";
        }

        return Cache::remember("book_cover_url:{$this->book_id}", now()->addDay(), function () {
            return $this->resolveGoogleBooksCover();
        });
    }

    public function getHasCoverAttribute()
    {
        return !empty($this->cover_url);
    }

    protected function resolveGoogleBooksCover()
    {
        $title = trim($this->title ?? '');
        $author = trim($this->author->author_name ?? '');

        if ($title === '') {
            return null;
        }

        $query = 'intitle:' . urlencode($title);
        if ($author !== '') {
            $query .= '+inauthor:' . urlencode($author);
        }

        try {
            $response = Http::timeout(3)->get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 1,
            ]);

            if ($response->successful() && isset($response['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                return preg_replace('#^http://#i', 'https://', $response['items'][0]['volumeInfo']['imageLinks']['thumbnail']);
            }
        } catch (\Throwable $e) {
            // ignore external lookup failure
        }

        return null;
    }
}