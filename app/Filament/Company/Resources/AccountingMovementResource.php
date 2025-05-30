<?php

namespace App\Filament\Company\Resources;

use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherTypeEnum;
use App\Filament\Company\Resources\AccountingMovementResource\Pages;
use App\Filament\Company\Resources\AccountingMovementResource\RelationManagers;
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
        $activeExercise = AccountingExercise::where('active', 1)
            ->where('company_id', filament()->getTenant()->id)
            ->first();
        $activePeriod = AccountingPeriod::where('active', 1)
            ->where('exercise_id', $activeExercise?->id)
            ->where('company_id', filament()->getTenant()->id)
            ->first();

        $minDate = $activePeriod ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->startOfMonth() : now()->startOfMonth();
        $maxDate = $activePeriod ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->endOfMonth() : now()->endOfMonth();

        return $form
            ->schema([
                Forms\Components\Section::make(__('Master Data'))
                    ->schema([
                        Forms\Components\Radio::make('type')
                            ->translateLabel()
                            ->options(VoucherTypeEnum::class)
                            ->inline()
                            ->required()
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
                            ->required(),
                        Forms\Components\Textarea::make('glosa')
                            ->translateLabel()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('Movements'))
                    ->schema([
                        Forms\Components\Repeater::make('movements')
                            ->relationship('movements')
                            ->schema([
                                Forms\Components\Select::make('accounting_account_id')
                                    ->label(__('Account'))
                                    ->options(AccountingAccount::where('company_id', filament()->getTenant()->id)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(2),
                                Forms\Components\Textarea::make('glosa')
                                    ->label(__('Description'))
                                    ->required()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('debit')
                                    ->label(__('Debit'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->reactive()
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
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ((float) $state > 0) {
                                            $set('debit', 0);
                                        }
                                    })
                                    ->disabled(fn(callable $get) => (float) $get('debit') > 0),
                            ])
                            ->columns(7)
                            ->columnSpanFull()
                            ->required()
                            ->itemLabel(fn() => null) // Remove individual item labels to show headers only once
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['company_id'] = filament()->getTenant()->id;
                                $data['debit'] = (float) ($data['debit'] ?? 0);
                                $data['credit'] = (float) ($data['credit'] ?? 0);
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                $data['company_id'] = filament()->getTenant()->id;
                                $data['debit'] = (float) ($data['debit'] ?? 0);
                                $data['credit'] = (float) ($data['credit'] ?? 0);
                                return $data;
                            }),
                    ]),
                Forms\Components\Placeholder::make('debit_total')
                    ->label(__('Total Debit'))
                    ->content(function (callable $get) {
                        $movements = $get('movements') ?? [];
                        return number_format(collect($movements)->sum(fn($item) => (float) ($item['debit'] ?? 0)), 2, '.', ',');
                    }),
                Forms\Components\Placeholder::make('credit_total')
                    ->label(__('Total Credit'))
                    ->content(function (callable $get) {
                        $movements = $get('movements') ?? [];
                        return number_format(collect($movements)->sum(fn($item) => (float) ($item['credit'] ?? 0)), 2, '.', ',');
                    }),
                Forms\Components\Placeholder::make('balance_status')
                    ->label(__('Balance Status'))
                    ->content(function (callable $get) {
                        $movements = $get('movements') ?? [];
                        $debitTotal = collect($movements)->sum(fn($item) => (float) ($item['debit'] ?? 0));
                        $creditTotal = collect($movements)->sum(fn($item) => (float) ($item['credit'] ?? 0));
                        return $debitTotal != $creditTotal ? __('Unbalanced: Debit and Credit totals do not match') : __('Balanced');
                    })
                    ->visible(function (callable $get) {
                        $movements = $get('movements') ?? [];
                        $debitTotal = collect($movements)->sum(fn($item) => (float) ($item['debit'] ?? 0));
                        $creditTotal = collect($movements)->sum(fn($item) => (float) ($item['credit'] ?? 0));
                        return $debitTotal != $creditTotal;
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
                    ->getStateUsing(fn($record) => number_format($record->balance, 2, '.', ','))
                    ->html()
                    ->suffix(fn($record) => $record->balance != 0 ? '<span class="text-danger-600 ml-2">(' . __('Unbalanced') . ')</span>' : ''),
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
            //
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
