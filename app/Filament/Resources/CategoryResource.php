<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 21;
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');
    }

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
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->maxLength(100),
                FileUpload::make('image')
                    ->translateLabel()
                    ->directory('categories')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenlabel(true)->color('warning')->tooltip(__('Edit')),
                Tables\Actions\ViewAction::make()->hiddenlabel(true)->color('primary')->tooltip(__('View')),
                Tables\Actions\DeleteAction::make()->hiddenlabel(true)->color('danger')->tooltip(__('Delete')),
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
