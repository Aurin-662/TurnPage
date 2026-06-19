<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $table = 'PUBLISHER';
    protected $primaryKey = 'publisher_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['publisher_id', 'publisher_name', 'address', 'phone', 'email', 'image', 'founded_date'];

    public function books()
    {
        return $this->hasMany(Book::class, 'publisher_id', 'publisher_id');
    }
}