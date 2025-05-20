<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingPeriodResource\Pages;
use App\Filament\Company\Resources\AccountingPeriodResource\RelationManagers;
use App\Models\AccountingPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingPeriodResource extends Resource
{
    protected static ?string $model = AccountingPeriod::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 42;


    public static function getNavigationLabel(): string
    {
        return __('Periods');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Periods');

    }
    public static function getModelLabel(): string
    {
        return __('Period');
    }


    public static function getNavigationGroup(): string
    {
        return __('Accounting');
    }


    // TODO:: Solo dejar un registro activo
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('exercise_id')
                        ->relationship('exercise', 'year')
                        ->required()
                        ->translateLabel()
                        ->searchable()
                        ->preload()
                        ->columnSpan(3),
                    Forms\Components\TextInput::make('month')
                        ->required()
                        ->translateLabel()
                        ->numeric()
                        ->minValue(01)
                        ->maxValue(12),
                    Forms\Components\Toggle::make('active'),
                ])->columns(10),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exercise.year')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('month')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->translateLabel(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exercise_id')
                    ->relationship('exercise', 'year')
                    ->preload()
                    ->translateLabel()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAccountingPeriods::route('/'),
            'create' => Pages\CreateAccountingPeriod::route('/create'),
            'edit' => Pages\EditAccountingPeriod::route('/{record}/edit'),
        ];
    }
}
