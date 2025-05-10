<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountSubType extends Model
{
    protected $table = 'account_sub_types';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'actount_type_id',
        'name',
        'description',
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class),
    }

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }


}
