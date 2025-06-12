<?php

namespace App\Filament\Company\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\VoucherTypeEnum;
use Filament\Resources\Resource;
use App\Models\AccountingMovement;
use Filament\Support\Enums\Alignment;
use App\Enums\VoucherDocumentTypeEnum;
use App\Enums\VoucherStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Company\Resources\AccountingMovementResource\Pages;
use App\Filament\Company\Resources\AccountingMovementResource\RelationManagers\ItemsRelationManager;
use App\Models\AccountingPeriod;
use App\Models\Label;
use Filament\Notifications\Notification;

class AccountingMovementResource extends Resource
{
    protected static ?string $model = AccountingMovement::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 42;

    public static function getNavigationLabel(): string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'polizas')
            ->value('value');

        return $label ?? __('Accounting Movements');
    }

    public static function getPluralLabel(): ?string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'polizas')
            ->value('value');

        return $label ?? __('Accounting Movements');
    }

    public static function getModelLabel(): string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'poliza')
            ->value('value');

        return $label ?? __('Accounting Movements');
    }

    public static function getNavigationGroup(): string
    {
        return __('Accounting');
    }

    public static function form(Form $form): Form
    {
        $activeExercise = filament()->getTenant()->getActiveExercise();
        // Obtener el mes de la fecha actual
        $currentMonth = now()->month;
        // Usar la relación periods de AccountingExercise para obtener el período activo
        $activePeriod = $activeExercise->periods()
            ->where('month', $currentMonth)
            ->first();

        // Establecer minDate y maxDate basados en el período activo o fecha actual
        $minDate = $activePeriod
            ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->startOfMonth()
            : now()->startOfMonth();
        $maxDate = $activePeriod
            ? \Carbon\Carbon::create($activeExercise->year, $activePeriod->month, 1)->endOfMonth()
            : now()->endOfMonth();

        // Generar el folio inicial
        $folio = $form->getRecord()
            ? $form->getRecord()->folio
            : ($activePeriod
                ? $activeExercise->year . str_pad($activePeriod->month, 2, '0', STR_PAD_LEFT) . str_pad($activePeriod->folio + 1, 4, '0', STR_PAD_LEFT)
                : $activeExercise->year . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '0001');

        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Radio::make('type')
                        ->translateLabel()
                        ->options(VoucherTypeEnum::class)
                        ->required()
                        ->default(VoucherTypeEnum::Both),
                    Forms\Components\Select::make('document_type')
                        ->translateLabel()
                        ->options(VoucherDocumentTypeEnum::class)
                        ->required(),
                    Forms\Components\Select::make('periodo')
                        ->translateLabel()
                        ->required()
                        ->options([
                            1 => __('January'),
                            2 => __('February'),
                            3 => __('March'),
                            4 => __('April'),
                            5 => __('May'),
                            6 => __('June'),
                            7 => __('July'),
                            8 => __('August'),
                            9 => __('September'),
                            10 => __('October'),
                            11 => __('November'),
                            12 => __('December'),
                        ])
                        ->default(now()->month)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) use ($activeExercise) {
                            // Usar la relación periods para buscar el período basado en el mes seleccionado
                            $period = $activeExercise->periods()
                                ->where('month', $state)
                                ->first();

                            if ($period) {
                                // Calcular minDate y maxDate
                                $minDate = \Carbon\Carbon::create($activeExercise->year, $period->month, 1)->startOfMonth();
                                $maxDate = \Carbon\Carbon::create($activeExercise->year, $period->month, 1)->endOfMonth();

                                // Actualizar el campo date
                                $set('date', [
                                    'minDate' => $minDate,
                                    'maxDate' => $maxDate,
                                    'default' => now()->between($minDate, $maxDate) ? now() : $minDate,
                                ]);

                                // Generar el folio: año + mes (2 dígitos) + folio+1 (4 dígitos)
                                $monthPadded = str_pad($period->month, 2, '0', STR_PAD_LEFT);
                                $newFolio = str_pad($period->folio + 1, 4, '0', STR_PAD_LEFT);
                                $folio = $activeExercise->year . $monthPadded . $newFolio;

                                // Actualizar el campo folio
                                $set('folio', $folio);
                            }
                        }),
                    Forms\Components\DatePicker::make('date')
                        ->translateLabel()
                        ->default(now())
                        ->minDate(function ($get) use ($activeExercise) {
                            $selectedMonth = $get('periodo') ?? now()->month;
                            return \Carbon\Carbon::create($activeExercise->year, $selectedMonth, 1)->startOfMonth();
                        })
                        ->maxDate(function ($get) use ($activeExercise) {
                            $selectedMonth = $get('periodo') ?? now()->month;
                            return \Carbon\Carbon::create($activeExercise->year, $selectedMonth, 1)->endOfMonth();
                        })
                        ->format('d-m-Y')
                        ->default(fn($get) => now()->greaterThan(
                            \Carbon\Carbon::create($activeExercise->year, $get('periodo') ?? now()->month, 1)->endOfMonth()
                        ) ? \Carbon\Carbon::create($activeExercise->year, $get('periodo') ?? now()->month, 1)->endOfMonth() : now())
                        ->required()
                        ->dehydrated(true),
                    Forms\Components\TextInput::make('folio')
                        ->translateLabel()
                        ->readOnly()
                        ->required()
                        ->default($folio)
                        ->dehydrated(true),
                    Forms\Components\Textarea::make('glosa')
                        ->translateLabel()
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(5),
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
                Tables\Actions\EditAction::make()->disabled(fn($record) => $record->applied()),

                Tables\Actions\DeleteAction::make()->disabled(fn($record) => $record->applied()),
                Tables\Actions\Action::make('apply')
                    ->label(__('Apply'))
                    // ->icon('heroicon-o-check-circle')
                    // ->icon('heroicon-o-hand-thumb-up')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->color('success')
                    ->disabled(fn($record) => !$record->canApply())
                    ->action(function ($record) {
                        try {
                            $record->apply();
                            $record->status = VoucherStatusEnum::FINISHED;
                            $record->save();
                            Notification::make()
                                ->title(__('Movement Applied'))
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(__('Error Applying Movement'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('Confirm Apply Movement'))
                    ->modalDescription(__('Are you sure you want to apply this accounting movement? This will update the account balances.')),

                Tables\Actions\Action::make('duplicate')
                    ->label(__('Duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->disabled(fn($record) => $record->status == VoucherStatusEnum::UNBALANCED)
                    ->action(function ($record) {
                        try {
                            $record->duplicate();
                            Notification::make()
                                ->title(__('Movement has been copied'))
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(__('Error Copying Movement'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('Confirm Duplicate Movement'))
                    ->modalDescription(__('Are you sure you want to duplicate this accounting entry?')),
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
