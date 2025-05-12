<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountSubtype extends Model
{
    protected $fillable = ['company_id', 'account_type_id', 'name', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subtype) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $subtype->company_id = filament()->getTenant()->id;
            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('account_subtypes.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(AccountingAccount::class);
    }
}
