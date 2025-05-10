<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccountingAccount extends Model
{
    protected $fillable = [
        'company_id',
        'account_subtype_id',
        'accounting_single_account_id',
        'code',
        'description',
        'is_analysis_code',
        'is_cost_center_required',
    ];

    protected $casts = [
        'is_analysis_code' => 'boolean',
        'is_cost_center_required' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
}
