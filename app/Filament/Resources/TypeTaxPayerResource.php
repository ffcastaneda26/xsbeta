<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeTaxPayerResource\Pages;
use App\Filament\Resources\TypeTaxPayerResource\RelationManagers;
use App\Models\Country;
use App\Models\TypeTaxPayer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeTaxPayerResource extends Resource
{
    protected static ?string $model = TypeTaxPayer::class;


    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 31;


    public static function getNavigationLabel(): string
    {
        return __('Types of Taxpayer');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Types of Taxpayer');

    }
    public static function getModelLabel(): string
    {
        return __('Type of Taxpayer');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }


    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->translateLabel(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(150)
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
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
                        return Country::whereHas('typeTaxPayers')
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
            'index' => Pages\ListTypeTaxPayers::route('/'),
            'create' => Pages\CreateTypeTaxPayer::route('/create'),
            'edit' => Pages\EditTypeTaxPayer::route('/{record}/edit'),
        ];
    }
}
