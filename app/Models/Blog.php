<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $table='blogs';
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'type_id',
        'category_id',
        'date',
        'author_id',
        'introduction',
        'description',
        'active',
        'image',
    ];

    protected function casts(): array
    {
        return [
             'created_at' => 'datetime:Y-m-d',
             'date' => 'datetime:Y-m-d',
        ];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,'author_id');
    }
}
