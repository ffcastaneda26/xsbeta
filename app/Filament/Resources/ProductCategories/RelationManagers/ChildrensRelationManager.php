<?php

namespace App\Filament\Resources\ProductCategories\RelationManagers;

use App\Filament\Resources\ProductCategories\Schemas\ProductCategoryForm;
use App\Filament\Resources\ProductCategories\Tables\ProductCategoriesTable;
use App\Filament\Resources\Products\Schemas\ProductForm as SchemasProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use App\Models\ProductCategory;
use Database\Factories\ProductCategoryFactory;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChildrensRelationManager extends RelationManager
{
    protected static string $relationship = 'children';
    protected static ?string $title = 'Categorías Hijas';

    public function form(Schema $schema): Schema
    {
        return ProductCategoryForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return ProductCategoriesTable::configure($table)
            ->headerActions([
               AttachAction::make()
                 ->modalHeading('Vincular Categoría')
                 ->form(fn(AttachAction $action) => [
                        Select::make('recordId')
                            ->label('Categoría')
                            ->options(
                                ProductCategory::whereNotIn('id', $this->getOwnerRecord()->children()->pluck('id'))->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona categoría'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DetachAction::make(),
            ]);
    }
}
