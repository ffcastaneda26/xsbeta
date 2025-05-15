<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionStatusFactory> */
    use HasFactory;  protected $table = 'transaction_statuses';
    public $timestamps = false;

    protected $fillable = ['company_id', 'name', 'description'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $role->company_id = filament()->getTenant()->id;
            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('transaction_statuses.company_id', filament()->getTenant()->id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


}
