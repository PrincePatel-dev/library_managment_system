<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Fields that can be mass assigned
    protected $fillable = [
        'title',
        'author',
        'category',
        'isbn',
        'quantity',
        'available_quantity',
        'description',
    ];

    // A book can be issued many times
    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    // Check if the book is available
    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }
}
