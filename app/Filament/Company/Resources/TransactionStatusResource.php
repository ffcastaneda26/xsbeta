<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\TransactionStatusResource\Pages;
use App\Filament\Company\Resources\TransactionStatusResource\RelationManagers;
use App\Models\TransactionStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionStatusResource extends Resource
{
    protected static ?string $model = TransactionStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 38;


    public static function getNavigationLabel(): string
    {
        return __('Transaction statements');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Transaction statements');

    }

    public static function getModelLabel(): string
    {
        return __('Transaction status');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->maxLength(100),

                Forms\Components\RichEditor::make('description')
                    ->translateLabel()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->translateLabel()
                    ->searchable()
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransactionStatuses::route('/'),
            'create' => Pages\CreateTransactionStatus::route('/create'),
            'edit' => Pages\EditTransactionStatus::route('/{record}/edit'),
        ];
    }
}
