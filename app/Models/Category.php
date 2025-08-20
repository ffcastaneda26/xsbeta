<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */

    use HasFactory;
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];


    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class);
    }
}
