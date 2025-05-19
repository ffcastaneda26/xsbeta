<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LabelByCountryResource\Pages;
use App\Filament\Resources\LabelByCountryResource\RelationManagers;
use App\Models\Country;
use App\Models\LabelByCountry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabelByCountryResource extends Resource
{
    protected static ?string $model = LabelByCountry::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 33;


    public static function getNavigationLabel(): string
    {
        return __('Labels by Country');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Labels by Country');

    }
    public static function getModelLabel(): string
    {
        return __('Label Country');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }



    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    public static function form(Form $form): Form
    {



         return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->translateLabel(),
                Forms\Components\TextInput::make('use_to')
                    ->required()
                    ->maxLength(150)
                    ->translateLabel(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->translateLabel(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(255)
                    ->translateLabel(),

            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('use_to')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->translateLabel()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country_id')
                    ->label(__('Country')) // O usa translateLabel() si prefieres
                    ->options(function () {
                        return Country::whereHas('labels')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('country_id', $data['value']);
                        }
                    })
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
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
            'index' => Pages\ListLabelByCountries::route('/'),
            'create' => Pages\CreateLabelByCountry::route('/create'),
            'edit' => Pages\EditLabelByCountry::route('/{record}/edit'),
        ];
    }
}
