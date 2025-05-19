<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Label extends Model
{
        protected $table = 'labels';
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


    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_label');
    }
}
