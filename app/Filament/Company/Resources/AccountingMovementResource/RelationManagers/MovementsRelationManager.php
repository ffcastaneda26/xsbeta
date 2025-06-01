<?php

namespace App\Filament\Company\Resources\AccountingMovementResource\RelationManagers;

use App\Models\AccountingAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Support\Enums\Alignment;

class MovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'movements';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('accounting_account_id')
                    ->label(__('Accounting Account'))
                    ->options(AccountingAccount::where('company_id', filament()->getTenant()->id)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->columnSpan(2),
                Forms\Components\Textarea::make('glosa')
                    ->label(__('Description'))
                    ->required()
                    ->columnSpan(3)
                    ->default(function () {
                        return $this->ownerRecord->glosa;
                    }),
                Forms\Components\TextInput::make('debit')
                    ->label(__('Debit'))
                    ->numeric()
                    ->step(0.01)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ((float) $state > 0) {
                            $set('credit', 0);
                        }
                    })
                    ->disabled(fn(callable $get) => (float) $get('credit') > 0),
                Forms\Components\TextInput::make('credit')
                    ->label(__('Credit'))
                    ->numeric()
                    ->step(0.01)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ((float) $state > 0) {
                            $set('debit', 0);
                        }
                    })
                    ->disabled(fn(callable $get) => (float) $get('debit') > 0),
            ])
            ->columns(7);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Accounting Items'))
            ->columns([
                TextColumn::make('account.ledger_account')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('glosa')
                    ->translateLabel()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('debit')
                    ->label(__('Debit'))
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignment(Alignment::End),
                TextColumn::make('credit')
                    ->label(__('Credit'))
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignment(Alignment::End),
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
                        return $data;
                    })
                    ->modalHeading(__('Create Accounting Item'))
                    ->label(__('Create') . ' ' . __('Accounting Item'))
                    ->button(),
                Tables\Actions\Action::make('save_parent')
                    ->label(__('Save Parent Record'))
                    ->action(function () {
                        // Aquí puedes disparar la lógica para guardar el formulario padre
                        $this->ownerRecord->save();
                        Notification::make()
                            ->title('Parent record saved')
                            ->success()
                            ->send();
                    })
                    ->color('primary')
                    ->button(),
            ])
            ->actions([
                EditAction::make()
                ->modalHeading(__('Edit Accounting Item')),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($record) {
    //         $record->movement->calculateTotals();
    //         $record->movement->updateStatus();
    //     });

    //     static::updated(function ($record) {
    //         $record->movement->calculateTotals();
    //         $record->movement->updateStatus();
    //     });

    //     static::deleted(function ($record) {
    //         $record->movement->calculateTotals();
    //         $record->movement->updateStatus();
    //     });
    // }
}
