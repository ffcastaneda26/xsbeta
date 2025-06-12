<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingPeriod extends Model
{
    protected $table = 'accounting_periods';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'exercise_id',
        'month',
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
                $builder->where('accounting_periods.company_id', filament()->getTenant()->id);
            }
        });

        static::updating(function ($record) {
            if ($record->active) {
                AccountingPeriod::where('company_id', filament()->getTenant()->id)
                    ->where('id', '!=', $record->id)
                    ->update(['active' => false]);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(AccountingExercise::class, 'exercise_id');
    }

    public function updateFolio(){
        $this->folio +=1;
        $this->save();
    }
}
