<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabelByCountry extends Model
{
        protected $table = 'labels_by_country';
    public $timestamps = false;

    protected $fillable = [
        'country_id',
        'use_to',
        'value',
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
