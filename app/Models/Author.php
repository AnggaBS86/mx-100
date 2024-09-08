<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 * 
 * This class used for entity of Author
 */
class Author extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'authors';
    protected $fillable = ['name', 'bio', 'birth_date'];

    /**
     * The Author has many books collection
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books(): HasMany 
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }
}
