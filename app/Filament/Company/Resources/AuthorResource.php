<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AuthorResource\Pages;
use App\Filament\Company\Resources\AuthorResource\RelationManagers;
use App\Models\Author;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?string $tenantOwnershipRelationship = 'company';
    protected static ?int $navigationSort = 11;

    public static function getNavigationLabel(): string
    {
        return __('Authors');
    }


    public static function getModelLabel(): string
    {
        return __('Author');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Authors');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Author::count();
        return $count > 0 ? (string) $count : '0';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Author::count() > 0 ? 'primary' : 'danger';
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
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
