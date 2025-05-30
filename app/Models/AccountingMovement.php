<?php

namespace App\Models;

use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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
            'document_type' => VoucherDocumentTypeEnum::class,
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

        static::creating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $record->company_id = filament()->getTenant()->id;
            }
            $record->user_id = Auth::user()->id;

            $activeExercise = AccountingExercise::where('active', 1)
                ->where('company_id', filament()->getTenant()->id)
                ->first();
            $activePeriod = AccountingPeriod::where('active', 1)
                ->where('exercise_id', $activeExercise?->id)
                ->where('company_id', filament()->getTenant()->id)
                ->first();

            $record->accounting_exercise_id = $activeExercise?->id;
            $record->accounting_period_id = $activePeriod?->id;

            $company = filament()->getTenant();
            $folioNumber = str_pad($company->folio + 1, 4, '0', STR_PAD_LEFT);
            $record->folio = "{$activeExercise->year}{$activePeriod->month}{$folioNumber}";

            $company->folio += 1;
            $company->save();
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

    public function movements(): HasMany
    {
        return $this->hasMany(AccountingMovementDetail::class);
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

    public function calculateTotals()
    {
        $this->debit = $this->movements->sum('debit');
        $this->credit = $this->movements->sum('credit');
        $this->balance = $this->calculateBalance();
        $this->status = $this->balance == 0 ? VoucherStatusEnum::PENDING : VoucherStatusEnum::INVALID;
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
