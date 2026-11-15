<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'isbn',
        'description',
        'cover_image',
        'published_year',
        'total_copies',
        'available_copies',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }
}
