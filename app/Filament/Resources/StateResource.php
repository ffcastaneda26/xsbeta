<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StateResource\RelationManagers;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';


    protected static ?int $navigationSort = 22;

    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('State');
    }


    public static function getPluralLabel(): ?string
    {
        return __('States');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable()->translateLabel(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->translateLabel(),
                Tables\Columns\TextColumn::make('latitude')->searchable()->sortable()->translateLabel(),
                Tables\Columns\TextColumn::make('longitude')->searchable()->sortable()->translateLabel(),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->preload()
                    ->translateLabel()
                    ->searchable(),
            ], layout: FiltersLayout::Modal)
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
