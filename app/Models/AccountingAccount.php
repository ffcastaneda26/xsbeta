<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccountingAccount extends Model
{

    protected $table = "accounting_accounts";

    protected $fillable = [
        'company_id',
        'account_type_id',
        'account_subtype_id',
        'accounting_single_account_id',
        'code',
        'ledger_account',
        'name',
        'description',
        'is_analysis_code',
        'is_cost_center_required',
        'parent_id',
    ];


    protected $casts = [
        'is_analysis_code' => 'boolean',
        'is_cost_center_required' => 'boolean',
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
                $builder->where('accounting_accounts.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function subtype(): BelongsTo
    {
        return $this->belongsTo(AccountSubtype::class, 'account_subtype_id');
    }

    public function singleAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingSingleAccount::class, 'accounting_single_account_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(AccountingCategory::class, 'accounting_account_category');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'parent_id');
    }


}
