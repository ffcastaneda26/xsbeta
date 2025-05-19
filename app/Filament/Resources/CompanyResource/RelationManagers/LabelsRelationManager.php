<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabelsRelationManager extends RelationManager
{
    protected static string $relationship = 'labels';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('use_to')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('use_to')
            ->columns([
                Tables\Columns\TextColumn::make('use_to')->translateLabel(),
                Tables\Columns\TextColumn::make('value')->translateLabel(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelect(function () {
                        return Forms\Components\Select::make('recordId')
                            ->label(__('Label'))
                            ->preload()
                            ->options(function () {
                                return \App\Models\Label::where('country_id', 44)
                                    ->pluck('value', 'id')
                                    ->toArray();
                            });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
