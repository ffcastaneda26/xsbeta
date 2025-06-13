<?php

namespace App\Models;

use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        static::updating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                // -----
                $company = filament()->getTenant();
                $activeExercise = $company->getActiveExercise();
                $activePeriod = $activeExercise->periods()->where('month', $record->date->month)->first();
                // Validar la fecha del movimiento
                if ($activeExercise && $activePeriod) {
                    $year = $activeExercise->year;
                    $month = $activePeriod->month;
                    $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
                    $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
                    $movementDate = Carbon::parse($record->date);

                    if ($movementDate->lt($startOfMonth) || $movementDate->gt($endOfMonth)) {
                        $record->status = VoucherStatusEnum::INVALID;
                        throw new \Exception("La fecha del movimiento debe estar dentro del rango del mes {$month} y año {$year}.");
                    }
                }
            }
        });

        static::creating(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $company = filament()->getTenant();
                $activeExercise = $company->getActiveExercise();
                $activePeriod = $activeExercise->periods()->where('month', $record->date->month)->first();
                $folioString = $activePeriod
                    ? $activeExercise->year . str_pad($record->date->month, 2, '0', STR_PAD_LEFT) . str_pad($activePeriod->folio + 1, 4, '0', STR_PAD_LEFT)
                    : $activeExercise->year . str_pad($record->date->month, 2, '0', STR_PAD_LEFT) . '0001';
                // Asigna valores al registro nuevo
                $record->company_id = filament()->getTenant()->id;
                $record->accounting_exercise_id = $activeExercise?->id;
                $record->accounting_period_id = $activePeriod?->id;
                $record->folio = $folioString;
                $record->status = VoucherStatusEnum::PENDING;
                $record->user_id = Auth::user()->id;
                if ($activeExercise && $activePeriod) {
                    $year = $activeExercise->year;
                    $month = $activePeriod->month;
                    $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
                    $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
                    $movementDate = Carbon::parse($record->date);
                    if ($movementDate->lt($startOfMonth) || $movementDate->gt($endOfMonth)) {
                        $record->status = VoucherStatusEnum::INVALID;
                        throw new \Exception("La fecha del movimiento debe estar dentro del rango del mes {$month} y año {$year}.");
                    }
                }
            }
        });
        static::created(function ($record) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                if ($record->period) {
                    $record->period->updateFolio();
                }
            }
        });
        /**
         *  Eliminar las partidas del movimiento contable
         */
        static::deleting(function ($record) {

            $record->Items()->each(function ($item) {
                $item->delete();
            });
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
        return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(AccountingMovementDetail::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(AccountingMovementDetail::class);
    }

    public function hasItems()
    {
        return $this->items->count();
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
        $this->debit = $this->items()->sum('debit');
        $this->credit = $this->items()->sum('credit');
        $this->balance = $this->debit - $this->credit;
        $this->save();
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

    public function updateStatus()
    {

        if ($this->items()->count()) {

            $this->status = $this->balance == 0
                ? VoucherStatusEnum::PENDING
                : VoucherStatusEnum::UNBALANCED;
        } else {
            $this->status = VoucherStatusEnum::INVALID;
        }
        $this->save();
    }

    public function applied()
    {
        return $this->status === VoucherStatusEnum::FINISHED;
    }


    public function validateDate()
    {
        $activeExercise = $this->exercise;
        $activePeriod = $this->period;

        if ($activeExercise && $activePeriod) {
            $year = $activeExercise->year;
            $month = $activePeriod->month;
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            $movementDate = Carbon::parse($this->date);

            if ($movementDate->lt($startOfMonth) || $movementDate->gt($endOfMonth)) {
                $this->status = VoucherStatusEnum::INVALID;
                $this->save();
                return false;
            }
        }
        return true;
    }

    public function canApply()
    {
        if (!$this->validateDate()) {
            return false;
        }

        return $this->status == VoucherStatusEnum::PENDING && $this->items()->count();
    }

    /**
     * Aplica movimiento
     * Recorre las partidas y por cada una:
     * (1) suma al debe o haber según corresponda de la cuenta
     * (2) Actualiza el saldo de la cuenta
     * @return void
     */
    public function apply()
    {
        foreach ($this->items as $item) {
            $account = $item->account;
            $type = $item->debit != 0 ? 'debit' : 'credit';
            $amount = $item->debit != 0 ? $item->debit : $item->credit;
            $account->update_amount($type, $amount);
        }
    }

    /**
     * Duplica movimiento contable
     * 1) Copia el registro actualizando:  Folio - Estado
     * 2) Copia las partidas del movimiento
     * 3) Actualiza folio en la empresa
     * @return AccountingMovement
     */
    public function duplicate(): self
    {
        DB::beginTransaction();
        try {
            $period = AccountingPeriod::find($this->accounting_period_id);
            $folio = $period->folio + 1;

            $newMovement = $this->replicate()->fill([
                'created_at' => now(),
                'updated_at' => now(),
                'folio' =>  $folio,
                'status' => VoucherStatusEnum::PENDING,
                'user_id' => Auth::user()->id,
            ]);

            $newMovement->save();

            foreach ($this->items as $item) {
                $newItem = $item->replicate();
                $newItem->accounting_movement_id = $newMovement->id;
                $newItem->save();
            }

            $period->updateFolio();
            DB::commit();
            return $newMovement;
        } catch (\Throwable $e) {
            DB::rollBack();
        }

        return $this;

    }

    /**
     * Reversa de los movimientos
     * Cambia de cargo a abono y viceversa
     * @return AccountingMovement
     */
    public function reverse(): self
    {
        DB::beginTransaction();
        try {

            foreach ($this->items as $item) {
                if($item->debit > 0){
                    $item->credit = $item->debit;
                    $item->debit = 0;
                }else{
                    $item->debit = $item->credit;
                    $item->credit = 0;
                }
                $item->save();
            }

            DB::commit();
            return $this;
        } catch (\Throwable $e) {
            DB::rollBack();
        }

        return $this;

    }
}
