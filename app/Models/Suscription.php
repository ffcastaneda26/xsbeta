<?php

namespace App\Models;

use App\Enums\Enums\SuscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscription extends Model
{
    protected $table = 'suscriptions';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'plan_id',
        'date',
        'amount',
        'status',
        'bill_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => SuscriptionStatusEnum::class,
            'amount' => 'decimal:2',
            'bill_date' => 'date',
            'date' => 'date',
        ];
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

}
