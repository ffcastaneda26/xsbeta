<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'active'
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
}
