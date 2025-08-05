<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
    ];

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
                $builder->where('categories.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class);
    }
}
