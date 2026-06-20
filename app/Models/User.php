<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'APP_USER';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'phone', 'image', 'role', 'created_at'
    ];

    protected $hidden = ['password'];
}