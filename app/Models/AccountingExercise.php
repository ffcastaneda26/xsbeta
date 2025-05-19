<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingExercise extends Model
{
    protected $table = 'accounting_exercises';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'year',
        'active'
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
                $builder->where('accounting_exercises.company_id', filament()->getTenant()->id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

        public function periods(): HasMany
    {
        return $this->hasMany(AccountingPeriod::class, 'exercise_id');
    }

}
