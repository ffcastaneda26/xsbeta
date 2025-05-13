<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingCategoryResource\Pages;
use App\Filament\Company\Resources\AccountingCategoryResource\RelationManagers;
use App\Models\AccountingCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingCategoryResource extends Resource
{
    protected static ?string $model = AccountingCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 24;
    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Categories');

    }
    public static function getModelLabel(): string
    {
        return __('Category');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('company_id', filament()->getTenant()->id);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
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
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAccountingCategories::route('/'),
            'create' => Pages\CreateAccountingCategory::route('/create'),
            'edit' => Pages\EditAccountingCategory::route('/{record}/edit'),
        ];
    }
}
