<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountTypeResource\Pages;
use App\Filament\Resources\AccountTypeResource\RelationManagers;
use App\Models\AccountType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountTypeResource extends Resource
{
    protected static ?string $model = AccountType::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 34;


    public static function getNavigationLabel(): string
    {
        return __('Account Types');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Account Types');

    }
    public static function getModelLabel(): string
    {
        return __('Account Type');
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
            'index' => Pages\ListAccountTypes::route('/'),
            'create' => Pages\CreateAccountType::route('/create'),
            'edit' => Pages\EditAccountType::route('/{record}/edit'),
        ];
    }
}
