<?php

namespace App\Filament\Resources\ProductCategories\RelationManagers;

use App\Filament\Resources\Products\Schemas\ProductForm as SchemasProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
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

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $title = 'Productos';

    public function form(Schema $schema): Schema
    {
        return SchemasProductForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return ProductsTable::configure($table)
            ->headerActions([
               AttachAction::make()
                 ->modalHeading('Vincular Producto')
                    ->form(fn(AttachAction $action) => [
                        Select::make('recordId')
                            ->label('Producto')
                            ->options(
                                Product::whereNotIn('id', $this->getOwnerRecord()->products()->pluck('id'))->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona un producto'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DetachAction::make(),
            ]);
    }
}
