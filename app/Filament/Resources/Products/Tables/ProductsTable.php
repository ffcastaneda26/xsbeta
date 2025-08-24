<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('short_description')
                    ->label('Descripción Corta')
                    ->limit(50)
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->searchable()
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                // TextColumn::make('stock')
                //     ->label('Existencia')
                //     ->numeric()
                //     ->alignEnd(),
                ImageColumn::make('images')
                    ->label('Imágenes')
                    ->getStateUsing(function ($record) {
                        if (!is_null($record->images) && is_array($record->images)) {
                            return collect($record->images)->map(function ($imagePath) {
                                return Storage::disk('public')->url($imagePath);
                            })->filter()->toArray(); // Filtra valores nulos o vacíos
                        }
                        return [];
                    })
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText(),

                IconColumn::make('is_active')
                    ->label('¿Activo?')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('categories', 'name')
                    ->label('Categoría')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
