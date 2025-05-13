<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingAccountResource\Pages;
use App\Filament\Company\Resources\AccountingAccountResource\RelationManagers;
use App\Models\AccountingAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingAccountResource extends Resource
{
    protected static ?string $model = AccountingAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('type.name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtype.name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_type_id')
                    ->relationship('type', 'name')
                    ->preload()
                    ->translateLabel()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('account_subtype_id')
                    ->relationship('subtype', 'name')
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
            'index' => Pages\ListAccountingAccounts::route('/'),
            'create' => Pages\CreateAccountingAccount::route('/create'),
            'edit' => Pages\EditAccountingAccount::route('/{record}/edit'),
        ];
    }
}
