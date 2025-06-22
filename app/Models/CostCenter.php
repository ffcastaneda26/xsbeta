<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    protected $table = 'cost_centers';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Asignar company_id al crear un registro
        static::creating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $record->company_id = filament()->getTenant()->id;
            }
        });

        // Ámbito global para multi-tenancy
        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('cost_centers.company_id', filament()->getTenant()->id);
            }
        });
    }

    // Relación con la compañía
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Relación con los movimientos contables
    public function movements(): HasMany
    {
        return $this->hasMany(AccountingMovementDetail::class, 'cost_center_id');
    }


}
