<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\CategoryResource\Pages;
use App\Filament\Company\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
        protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?string $tenantOwnershipRelationship = 'company';
    protected static ?int $navigationSort = 12;

    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }


    public static function getModelLabel(): string
    {
        return __('Category');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Categories');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Category::count();
        return $count > 0 ? (string) $count : '0';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Category::count() > 0 ? 'primary' : 'danger';
    }
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }




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
                //
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
