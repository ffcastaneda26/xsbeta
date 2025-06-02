<?php

namespace App\Filament\Company\Resources;

use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherTypeEnum;
use App\Filament\Company\Resources\AccountingMovementResource\Pages;
use App\Filament\Company\Resources\AccountingMovementResource\RelationManagers;
use App\Filament\Company\Resources\AccountingMovementResource\RelationManagers\ItemsRelationManager;
use App\Filament\Company\Resources\AccountingMovementResource\RelationManagers\MovementsRelationManager;
use App\Models\AccountingAccount;
use App\Models\AccountingExercise;
use App\Models\AccountingMovement;
use App\Models\AccountingPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingMovementResource extends Resource
{
    protected static ?string $model = AccountingMovement::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 42;

    public static function getNavigationLabel(): string
    {
        return __('Accounting Movements');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Accounting Movements');
    }

    public static function getModelLabel(): string
    {
        return __('Accounting Movement');
    }

    public static function getNavigationGroup(): string
    {
        return __('Accounting');
    }

    public static function form(Form $form): Form
    {
        $activeExercise = filament()->getTenant()->getActiveExercise();
        $activePeriod = filament()->getTenant()->getActivePeriod();
        $minDate = $activePeriod ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->startOfMonth() : now()->startOfMonth();
        $maxDate = $activePeriod ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->endOfMonth() : now()->endOfMonth();
        $folio = filament()->getTenant()->getFolioToAccountingMovement();
        $folio = $form->getRecord() ? $form->getRecord()->folio : filament()->getTenant()->getFolioToAccountingMovement();

        return $form
            ->schema([
                Forms\Components\Section::make(__('Folio: ') . $folio)
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Radio::make('type')
                                ->translateLabel()
                                ->options(VoucherTypeEnum::class)
                                ->inline()
                                ->required()
                                ->default(VoucherTypeEnum::Both)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('document_type')
                                ->translateLabel()
                                ->options(VoucherDocumentTypeEnum::class)
                                ->required(),
                            Forms\Components\DatePicker::make('date')
                                ->translateLabel()
                                ->default(now())
                                ->maxDate($maxDate)
                                ->minDate($minDate)
                                ->format('d-m-Y')
                                ->default(fn() => now()->greaterThan($maxDate) ? $maxDate : now())
                                ->required(),
                        ])->columns(2),
                        Forms\Components\Group::make()->schema([
                            Forms\Components\Textarea::make('glosa')
                                ->translateLabel()
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ])->columns(2),

                Forms\Components\Section::make()->schema([
                    Forms\Components\Placeholder::make('debit_total')
                        ->label(__('Total Debit'))
                        ->content(function ($record) {
                            return number_format($record?->movements()->sum('debit') ?? 0, 2, '.', ',');
                        }),

                    Forms\Components\Placeholder::make('credit_total')
                        ->label(__('Total Credit'))
                        ->content(function ($record) {
                            return number_format($record?->movements()->sum('credit') ?? 0, 2, '.', ',');
                        }),

                    Forms\Components\Placeholder::make('')->content(function ($record) {
                        $debitTotal = $record?->movements()->sum('debit') ?? 0;
                        $creditTotal = $record?->movements()->sum('credit') ?? 0;
                        return view('filament.company.resources.accounting-movement.unbalanced_message', [
                            'message' => $debitTotal != $creditTotal ? __('Unbalanced Movement') : __('Checked'),
                            'error' => $debitTotal != $creditTotal,
                        ]);
                    }),
                ])->columns(4)
                    ->visible(function ($livewire, $record) {
                        return $livewire instanceof \Filament\Resources\Pages\EditRecord && $record?->movements()->exists();
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('folio')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_type')
                    ->searchable()
                    ->sortable()
                    ->label(__('Document')),
                Tables\Columns\TextColumn::make('debit')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('credit')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('Balance'))
                    ->getStateUsing(fn($record) => number_format($record->balance, 2, '.', ',')),
                Tables\Columns\TextColumn::make('status')
                    ->translateLabel()
                    ->color(fn($record) => $record->status_color),
                Tables\Columns\TextColumn::make('glosa')
                    ->translateLabel()
                    ->wrap()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d-m-Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime('d-m-Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                // MovementsRelationManager::class,
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountingMovements::route('/'),
            'create' => Pages\CreateAccountingMovement::route('/create'),
            'edit' => Pages\EditAccountingMovement::route('/{record}/edit'),
        ];
    }
}
