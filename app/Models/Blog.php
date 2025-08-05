<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';

    protected $fillable = [
        'company_id',
        'author_id',
        'title',
        'slug',
        'description',
        'content',
        'image',
        'is_published',
        'published_at'


    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'date',
        ];
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $role->company_id = filament()->getTenant()->id;
            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('blogs.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
