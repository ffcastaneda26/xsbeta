<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'numeric_code',
        'currency_symbol',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'tld',
        'native',
        'region',
        'subregion',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
    ];


    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(Tax::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function typeTaxPayers()
    {
        return $this->hasMany(TypeTaxPayer::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}


