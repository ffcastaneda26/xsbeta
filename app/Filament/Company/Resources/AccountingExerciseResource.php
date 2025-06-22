<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingExerciseResource\Pages;
use App\Filament\Company\Resources\AccountingExerciseResource\RelationManagers;
use App\Filament\Company\Resources\AccountingExerciseResource\RelationManagers\PeriodsRelationManager;
use App\Models\AccountingExercise;
use App\Models\Label;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingExerciseResource extends Resource
{
    protected static ?string $model = AccountingExercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 41;

    public static function getNavigationLabel(): string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'exercises')
            ->value('value');

        return $label ?? __('Accounting Movements');
    }

    public static function getPluralLabel(): ?string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'exercises')
            ->value('value');

        return $label ?? __('Accounting Movements');
    }

    public static function getModelLabel(): string
    {
        $tenant = filament()->getTenant();
        $countryId = $tenant->country_id;

        $label = Label::where('country_id', $countryId)
            ->where('use_to', 'exercise')
            ->value('value');

        return $label ?? __('Exercise');
    }

    public static function getNavigationGroup(): string
    {
        return __('Accounting');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('year')
                        ->required()
                        ->translateLabel()
                        ->numeric()
                        ->unique(
                            ignoreRecord: true,
                            table: 'accounting_exercises',
                            column: 'year',
                            modifyRuleUsing: function ($rule) {
                                return $rule->where('company_id', filament()->getTenant()->id);
                            }
                        )
                        ->validationMessages([
                            'unique' => __('Period Already Exists'),
                        ])
                        ->minValue(2020)
                        ->maxValue(2099),
                    Forms\Components\Toggle::make('active')
                        ->reactive() // Make the toggle reactive
                        ->afterStateUpdated(function ($state, $livewire) {
                            // Dispatch an event to refresh the relation manager
                            $livewire->dispatch('refreshRelationManager', [
                                'name' => 'periods', // Target the 'periods' relation manager
                            ]);
                        }),
                ])->columns(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('warning')
                    ->after(function ($livewire) {
                        $livewire->dispatch('refreshRelationManager', [
                            'name' => 'periods',
                        ]);
                    }),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn($record) => __('Activate'))
                    ->action(function ($record) {
                        $record->update(['active' => !$record->active]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn($record) => __('Activate Exercise'))
                    ->modalDescription(fn($record) => __('Are you sure you want to activate this exercise? This will deactivate all other exercises for this company.'))
                    ->modalSubmitActionLabel(__('Activate'))
                    ->visible(fn($record) => !$record->active)
                    ->button()
                    ->color(color: 'danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PeriodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountingExercises::route('/'),
            'create' => Pages\CreateAccountingExercise::route('/create'),
            'edit' => Pages\EditAccountingExercise::route('/{record}/edit'),
        ];
    }
}