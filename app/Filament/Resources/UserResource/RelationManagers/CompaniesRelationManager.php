<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Companies');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Companies'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelect(function () {
                        return Forms\Components\Select::make('recordId')
                            ->label(__('Company'))
                            ->options(function () {
                                return \App\Models\Company::where('active', 1)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable();
                    })
                    ->visible(function () {
                        return \App\Models\Company::where('active', 1)->exists();
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
