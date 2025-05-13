<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccountingCategory extends Model
{
    protected $table = 'accounting_categories';
    protected $fillable = ['company_id', 'name', 'description'];

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
                $builder->where('accounting_categories.company_id', filament()->getTenant()->id);
            }
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(AccountingAccount::class, 'accounting_account_category');
    }


}
