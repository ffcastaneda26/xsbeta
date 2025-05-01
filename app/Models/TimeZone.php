<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeZone extends Model
{
    protected $table = 'time_zones';
    public $timestamps = false;

    protected $fillable = [
        'time_zone',
        'continent',
        'zone',
        'use',
    ];

    public function companies():HasMany
    {
        return $this->hasMany(Company::class);
    }
}
