<?php

namespace App\Models;

use App\Enums\IntervalPlanEnum;
use App\Enums\PlanTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'plans';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'currency',
        'plan_type',
        'days',
        'description',
        'image',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'plan_type' => PlanTypeEnum::class,
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function suscriptions(): HasMany
    {
        return $this->hasMany(Suscription::class);
    }

    public function getPlanTypelAttribute($value): PlanTypeEnum
    {
        return PlanTypeEnum::from($value);
    }

}


