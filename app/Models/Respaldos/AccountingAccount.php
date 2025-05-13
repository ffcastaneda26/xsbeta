<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingAccount extends Model
{
    /** @use HasFactory<\Database\Factories\AccountingAccountFactory> */
    use HasFactory;

    protected $table = 'accounting_accounts';

    protected $fillable = [
        'company_id',
        'account_type_id',
        'code',
        'name',
        'description',
        'active',
        'parent_id'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $record->company_id = filament()->getTenant()->id;

            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('company_id', filament()->getTenant()->id);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }
    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function subtype()
    {
        return $this->belongsTo(AccountSubType::class, 'account_subtype_id');
    }

        public function singleaccount()
    {
        return $this->belongsTo(AccountingSingleAccount::class, 'accounting_single_account_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'parent_id');
    }

    public function childs(): HasMany
    {

        return $this->hasMany(AccountingAccount::class, 'parent_id');
    }
}
