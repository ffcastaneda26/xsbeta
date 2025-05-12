<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingSingleAccount extends Model
{
    protected $table = 'accounting_single_accounts';
    public $timestamps = false;

    protected $fillable = ['company_id', 'account_type_id', 'name', 'description', 'code'];


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
                $builder->where('accounting_single_accounts.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
    public function Type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function sigleAccounts(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(AccountingAccount::class);
    }



}
