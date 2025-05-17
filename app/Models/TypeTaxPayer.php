<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeTaxPayer extends Model
{
    protected $table = 'type_tax_payers';
    public $timestamps = false;

    protected $fillable = [
        'country_id',
        'name',
        'description',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function companies():HasMany
    {
        return $this->hasMany(Company::class);
    }
}
