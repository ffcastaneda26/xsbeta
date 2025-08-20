<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                                   ->label('Título')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image')
                 ->label('Imagen')
                    ->circular(),
                IconColumn::make('is_published')
                ->label('¿Publicado?')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->label('Publicado el')
                    ->sortable()
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Creado el')
                    ->sortable()
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
            ->dateTime()
                    ->label('Actualizado el')
                    ->sortable()
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Estado de Publicación')
                    ->options([
                        true => 'Publicado',
                        false => 'No Publicado',
                    ])
                    ->native(false),
                Filter::make('published_at')
                    ->form([
                        DatePicker::make('published_from')
                            ->label('Publicado desde')
                            ->placeholder(fn ($state): string => 'Ene 1, ' . now()->subYear()->format('Y')),
                        DatePicker::make('published_until')
                            ->label('Publicado hasta')
                            ->placeholder(fn ($state): string => 'Dic 31, ' . now()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
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
