<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;
    protected $table = 'authors';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
