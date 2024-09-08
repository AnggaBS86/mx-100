<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * class Book
 * 
 * This class used for entity of `book` table
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $fillable = ['title', 'description', 'publish_date', 'author_id'];

    /**
     * The relationship is belongs to Author
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo 
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
