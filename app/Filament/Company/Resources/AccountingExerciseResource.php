<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingExerciseResource\Pages;
use App\Filament\Company\Resources\AccountingExerciseResource\RelationManagers;
use App\Models\AccountingExercise;
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
        return __('Exercises');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Exercises');

    }
    public static function getModelLabel(): string
    {
        return __('Exercise');
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
                    Forms\Components\TextInput::make('year')
                        ->required()
                        ->translateLabel()
                        ->numeric()
                        ->minValue(2020)
                        ->maxValue(2099),
                    Forms\Components\Toggle::make('active'),
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
            'index' => Pages\ListAccountingExercises::route('/'),
            'create' => Pages\CreateAccountingExercise::route('/create'),
            'edit' => Pages\EditAccountingExercise::route('/{record}/edit'),
        ];
    }
}
