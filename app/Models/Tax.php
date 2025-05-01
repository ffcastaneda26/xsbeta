<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    protected $table = 'taxes';
    public $timestamps = false;

    protected $fillable = [
        'country_id',
        'name',
        'min_length',
        'max_length',
        'regex',
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
