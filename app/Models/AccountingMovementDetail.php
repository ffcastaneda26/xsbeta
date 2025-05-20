<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingMovementDetail extends Model
{
    protected $table = 'accounting_movement_details';

    protected $fillable = [
        'company_id',
        'accounting_movement_id',
        'accounting_account_id',
        'glosa',
        'debit',
        'credit'
    ];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
            'balance' => 'decimal:2',
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
                $builder->where('accounting_movement_details.company_id', filament()->getTenant()->id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function movement(): BelongsTo
    {
        return $this->belongsTo(AccountingMovement::class);
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class);
    }
}

