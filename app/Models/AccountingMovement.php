<?php

namespace App\Models;

use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingMovement extends Model
{
    protected $table = 'accounting_movements';

    protected $fillable = [
        'company_id',
        'accounting_exercise_id',
        'accounting_period_id',
        'type',
        'document_type',
        'folio',
        'date',
        'glosa',
        'debit',
        'credit',
        'balance',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => VoucherTypeEnum::class,
            'document_type' => VoucherTypeEnum::class,
            'status' => VoucherStatusEnum::class,
            'date' => 'date',
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

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
                $builder->where('accounting_movements.company_id', filament()->getTenant()->id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(AccountingExercise::class);
    }
    public function period(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setDebitAttribute($value)
    {
        $this->attributes['debit'] = $value;
        $this->attributes['balance'] = $this->calculateBalance();
    }

    public function setCreditAttribute($value)
    {
        $this->attributes['credit'] = $value;
        $this->attributes['balance'] = $this->calculateBalance();
    }

    protected function calculateBalance()
    {
        return bcsub(
            $this->attributes['debit'] ?? 0,
            $this->attributes['credit'] ?? 0,
            2
        );
    }
    public function getTypeLabelAttribute(): string
    {
        return $this->type->getLabel();
    }
    public function getStatusLabelAttribute(): string
    {
        return $this->status->getLabel();
    }
    public function getStatusColorAttribute(): string
    {
        return $this->status->getColor();
    }
    public function getDocumentTypeLabelAttribute(): string
    {
        return $this->document_type->getLabel();
    }
    public function getDocumentTypeColorAttribute(): string
    {
        return $this->document_type->getColor();
    }
    public function getDocumentTypeIconAttribute(): string
    {
        return $this->document_type->getIcon();
    }
    public function getTypeIconAttribute(): string
    {
        return $this->type->getIcon();
    }
    public function getTypeColorAttribute(): string
    {
        return $this->type->getColor();
    }
    public function getTypeClassAttribute(): string
    {
        return $this->type->getClass();
    }
    public function getStatusClassAttribute(): string
    {
        return $this->status->getClass();
    }
    public function getDocumentTypeClassAttribute(): string
    {
        return $this->document_type->getClass();
    }
    public function getTypeIconColorAttribute(): string
    {
        return $this->type->getIconColor();
    }
    public function getStatusIconColorAttribute(): string
    {
        return $this->status->getIconColor();
    }
    public function getDocumentTypeIconColorAttribute(): string
    {
        return $this->document_type->getIconColor();
    }
    public function getTypeIconClassAttribute(): string
    {
        return $this->type->getIconClass();
    }
    public function getStatusIconClassAttribute(): string
    {
        return $this->status->getIconClass();
    }
    public function getDocumentTypeIconClassAttribute(): string
    {
        return $this->document_type->getIconClass();
    }

}
