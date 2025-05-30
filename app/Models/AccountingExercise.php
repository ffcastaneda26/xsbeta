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

        // Creando asigna el id de la compañia
        static::creating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $record->company_id = filament()->getTenant()->id;
            }
            if ($record->active) {
                AccountingExercise::where('company_id', filament()->getTenant()->id)
                    ->where('id', '!=', $record->id)
                    ->update(['active' => false]);
            }
        });

        // Cuando se crea un ejercicio contable, se crean los periodos contables
        static::created(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                for ($i = 1; $i <= 12; $i++) {
                    AccountingPeriod::create([
                        'company_id' => filament()->getTenant()->id,
                        'exercise_id' => $record->id,
                        'month' => $i,
                        'active' => false,
                    ]);
                }
            }
        });

        // Cuando se borra un ejercicio contable, se borran los periodos contables
        static::deleting(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $record->periods()->delete();
            }
        });

        // Nos aseguramos que solo se muestren los ejercicios contables de la compañia
        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('accounting_exercises.company_id', filament()->getTenant()->id);
            }
        });

        // Al actualizar un ejercicio contable, y se puso como "Activo" se desactivan los demas ejercicios contables
        static::updating(function ($record) {
            if ($record->active) {
                AccountingExercise::where('company_id', filament()->getTenant()->id)
                    ->where('id', '!=', $record->id)
                    ->update(['active' => false]);
                // Desactivar todos los períodos de este ejercicio
                $record->periods()->update(['active' => false]);
            }
        });

        static::addGlobalScope('activeExercise', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('accounting_exercises.company_id', filament()->getTenant()->id)
                    ->where('accounting_exercises.active', 1);
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

    public function activeExercise($builder)
    {
        if (filament()->getCurrentPanel()->getId() === 'company') {
            $builder->where('accounting_exercises.company_id', filament()->getTenant()->id)
                ->where('active', 1)->first();
        }
        return $this->where('active', 1)->first();
    }

}
