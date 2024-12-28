<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Type;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TypeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TypeResource\RelationManagers;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 20;
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');
    }

    public static function getNavigationLabel(): string
    {
        return __('Types');
    }
    public static function getModelLabel(): string
    {
        return __('Type');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Types');
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
                    ->directory('types')
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
            'index' => Pages\ListTypes::route('/'),
            'create' => Pages\CreateType::route('/create'),
            'edit' => Pages\EditType::route('/{record}/edit'),
        ];
    }
}
