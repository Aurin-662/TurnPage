<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'CATEGORY';
    protected $primaryKey = 'CATEGORY_ID';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'CATEGORY_NAME',
        'DESCRIPTION',
        'ICON',
        'DISPLAY_ORDER',
        'IS_ACTIVE'
    ];

    protected $casts = [
        'IS_ACTIVE' => 'boolean',
        'DISPLAY_ORDER' => 'integer'
    ];

    /**
     * Get all books in this category
     * Uses the BOOK_CATEGORY junction table
     */
    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'BOOK_CATEGORY',
            'CATEGORY_ID',
            'BOOK_ID',
            'CATEGORY_ID',
            'BOOK_ID'
        );
    }

    /**
     * Get active categories only
     */
    public static function active()
    {
        return self::where('IS_ACTIVE', 1)->orderBy('DISPLAY_ORDER');
    }

    /**
     * Get category by name
     */
    public static function findByName($name)
    {
        return self::where('CATEGORY_NAME', $name)->first();
    }
}
