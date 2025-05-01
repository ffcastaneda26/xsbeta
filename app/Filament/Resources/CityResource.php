<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CityResource\RelationManagers;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';


    protected static ?int $navigationSort = 23;

    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('City');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Cities');
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
                Tables\Columns\TextColumn::make('state.name')->searchable()->sortable()->translateLabel(),
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
                Tables\Filters\SelectFilter::make('state_id')
                    ->relationship('state', 'name')
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
