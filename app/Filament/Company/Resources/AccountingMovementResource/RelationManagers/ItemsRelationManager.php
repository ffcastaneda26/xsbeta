<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Label;
use Filament\Forms\Form;
use App\Models\CostCenter;
use Filament\Tables\Table;
use App\Enums\VoucherTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Models\AccountingAccount;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Resources\RelationManagers\RelationManager;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;
        $decimalsInAmounts = $tenant->decimals_in_amounts ?? 0; // Obtener decimals_in_amounts, por defecto 0

        $debit_label = Label::where('country_id', $countryId)
            ->where('use_to', 'debe')
            ->value('value') ?? __('Debit');

        $credit_label = Label::where('country_id', $countryId)
            ->where('use_to', 'haber')
            ->value('value') ?? __('Credit');

        $glosa_label = Label::where('country_id', $countryId)
            ->where('use_to', 'glosa')
            ->value('value') ?? __('Glosa');

        // Determinar el step y la validación según decimals_in_amounts
        $step = $decimalsInAmounts > 0 ? pow(10, -$decimalsInAmounts) : 1; // Ej: 0.01 para 2 decimales
        $decimalValidation = $decimalsInAmounts > 0 ? "decimal:0,{$decimalsInAmounts}" : 'integer';

        // Expresión regular para validar el número de decimales
        $regex = $decimalsInAmounts > 0
            ? '/^-?\d+(\.\d{1,' . $decimalsInAmounts . '})?$/'
            : '/^-?\d+$/'; // Solo enteros si decimals_in_amounts es 0

        return $form
            ->schema([
                Forms\Components\Select::make('accounting_account_id')
                    ->label(__('Accounting Account'))
                    ->options(AccountingAccount::where('company_id', filament()->getTenant()->id)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->columnSpan(2),
                Forms\Components\Select::make('cost_center_id')
                    ->label(__('Cost Center'))
                    ->options(CostCenter::where('company_id', filament()->getTenant()->id)->pluck('name', 'id'))
                    ->required(function (callable $get) {
                        $accountId = $get('accounting_account_id');
                        if ($accountId) {
                            return AccountingAccount::where('id', $accountId)
                                ->where('company_id', filament()->getTenant()->id)
                                ->value('is_cost_center_required') ?? false;
                        }
                        return false;
                    })
                    ->searchable()
                    ->columnSpan(2),
                Forms\Components\Textarea::make('glosa')
                    ->label(__($glosa_label))
                    ->required()
                    ->rows(1)
                    ->columnSpan(2)
                    ->default(function () {
                        return $this->ownerRecord->glosa;
                    }),
                Forms\Components\TextInput::make('debit')
                    ->label(__($debit_label))
                    ->numeric()
                    ->step($step)
                    ->rules([
                        'numeric',
                        'min:0',
                        $decimalValidation,
                        'regex:' . $regex,
                    ])
                    ->formatStateUsing(function ($state) use ($decimalsInAmounts) {
                        return $state !== null ? number_format($state, $decimalsInAmounts, '.', '') : null;
                    })
                    ->dehydrateStateUsing(function ($state) use ($decimalsInAmounts) {
                        return $state !== null ? round((float) $state, $decimalsInAmounts) : 0;
                    })
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ((float) $state > 0) {
                            $set('credit', 0);
                        }
                    })
                    ->disabled(fn(callable $get) => (float) $get('credit') > 0)
                    ->extraAttributes(['min' => 0]),
                Forms\Components\TextInput::make('credit')
                    ->label(__($credit_label))
                    ->numeric()
                    ->step($step)
                    ->rules([
                        'numeric',
                        'min:0',
                        $decimalValidation,
                        'regex:' . $regex,
                    ])
                    ->formatStateUsing(function ($state) use ($decimalsInAmounts) {
                        return $state !== null ? number_format($state, $decimalsInAmounts, '.', '') : null;
                    })
                    ->dehydrateStateUsing(function ($state) use ($decimalsInAmounts) {
                        return $state !== null ? round((float) $state, $decimalsInAmounts) : 0;
                    })
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ((float) $state > 0) {
                            $set('debit', 0);
                        }
                    })
                    ->disabled(fn(callable $get) => (float) $get('debit') > 0)
                    ->extraAttributes(['min' => 0]),
            ])
            ->columns(8);
    }

    public function table(Table $table): Table
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;
        $decimalsInAmounts = $tenant->decimals_in_amounts ?? 0; // Obtener decimals_in_amounts, por defecto 0

        $debit_label = Label::where('country_id', $countryId)
            ->where('use_to', 'debe')
            ->value('value');
        $debit_label ?? __('Credit');


        $credit_label = Label::where('country_id', $countryId)
            ->where('use_to', 'haber')
            ->value('value');
        $credit_label ?? __('Credit');
        $glosa_label = Label::where('country_id', $countryId)
            ->where('use_to', 'glosa')
            ->value('value');
        $glosa_label ?? __('Glosa');

        return $table
            ->heading(__('Accounting Items'))
            ->columns([
                Tables\Columns\TextColumn::make('account.ledger_account')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costCenter.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('glosa')
                    ->label(__($glosa_label))
                    ->wrap()
                    ->limit(50),
                Tables\Columns\TextColumn::make('debit')
                    ->label(__($debit_label))
                    ->numeric(decimalPlaces: $decimalsInAmounts, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignment(Alignment::End)
                    ->summarize(Sum::make()
                        ->label('')->extraAttributes(['class' => 'font-bold'])),
                Tables\Columns\TextColumn::make('credit')
                    ->label(__($credit_label))
                    ->numeric(decimalPlaces: $decimalsInAmounts, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignment(Alignment::End)
                    ->summarize(Sum::make()
                        ->label('')->extraAttributes(['class' => 'font-bold'])),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['company_id'] = filament()->getTenant()->id;
                        $data['debit'] = (float) ($data['debit'] ?? 0);
                        $data['credit'] = (float) ($data['credit'] ?? 0);
                        // Forzar que solo uno de los dos campos tenga un valor mayor a 0
                        if ($data['debit'] > 0) {
                            $data['credit'] = 0;
                        } elseif ($data['credit'] > 0) {
                            $data['debit'] = 0;
                        }
                        return $data;
                    })
                    ->after(function () {
                        $this->updateParentRecord();
                        $this->dispatch('refresh-relation-manager');
                    })
                    ->modalHeading(__('Create Accounting Item'))
                    ->label(__('Create') . ' ' . __('Accounting Item'))
                    ->button(),
            ])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['debit'] = (float) ($data['debit'] ?? 0);
                        $data['credit'] = (float) ($data['credit'] ?? 0);
                        // Forzar que solo uno de los dos campos tenga un valor mayor a 0
                        if ($data['debit'] > 0) {
                            $data['credit'] = 0;
                        } elseif ($data['credit'] > 0) {
                            $data['debit'] = 0;
                        }
                        return $data;
                    })
                    ->modalHeading(__('Edit Accounting Item'))
                    ->after(function () {
                        $this->updateParentRecord();
                        $this->dispatch('refresh-relation-manager');
                    }),
                DeleteAction::make()
                    ->after(function () {
                        $this->updateParentRecord();
                        $this->dispatch('refresh-relation-manager');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function () {
                        $this->updateParentRecord();
                        $this->dispatch('refresh-relation-manager');
                    }),
            ])
            ->contentFooter(function () {
                $items = $this->ownerRecord->items;
                $debitTotal = $items->sum('debit');
                $creditTotal = $items->sum('credit');
                $isBalanced = $debitTotal === $creditTotal;

                return !$isBalanced ? view('filament.company.resources.accounting-movement.unbalanced_message', [
                    'message' => __('Unbalanced Movement'),
                    'error' => true
                ]) : null;
            });
    }

    /**
     * Update the parent AccountingMovement record's totals and status.
     */
    protected function updateParentRecord(): void
    {
        $record = $this->ownerRecord;

        $movements = $this->ownerRecord->movements;

        if (!$movements->count()) {
            $record->calculateTotals();
            $record->updateStatus();
            return;
        }

        $movements = $this->ownerRecord->movements;
        $debitTotal = $movements->sum('debit');
        $creditTotal = $movements->sum('credit');
        $isBalanced = $debitTotal === $creditTotal;

        // Update parent record fields
        $this->ownerRecord->update([
            'status' => $isBalanced ? VoucherStatusEnum::PENDING : VoucherStatusEnum::UNBALANCED,
            'debit' => $debitTotal,
            'credit' => $creditTotal,
        ]);

        // TODO:: Revisar si se desea avisar o no por este medio
        if (!$isBalanced) {
            Notification::make()
                ->title(__('Unbalanced'))
                ->body(__('The debit and credit totals do not match.'))
                ->warning()
                ->send();
        }
    }


}