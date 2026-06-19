<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'AUTHOR';
    protected $primaryKey = 'author_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['author_id', 'author_name', 'bio', 'birth_date', 'country', 'image'];

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id', 'author_id');
    }
}