<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Permissionx;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;

class PermissionResource extends Resource
{
    protected static ?string $model = Permissionx::class;
    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 13;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasrole('Super Admin') || Auth::user()->hasrole('Administrador');
    }
    public static function getNavigationGroup(): string
    {
        return __('Security');
    }
    public static function getNavigationLabel(): string
    {
        return __('Permissions');
    }


    public static function getModelLabel(): string
    {
        return __('Permission');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Permissions');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->minLength(5)
                            ->translateLabel(),
                        Select::make('roles')
                            ->multiple()
                            ->relationship(titleAttribute: 'name')
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->unique()
                            ]),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('users_count')->counts('users')->label('Usuarios'),
                TextColumn::make('roles.name')->label('Roles'),
            ])
            ->filters([
                SelectFilter::make(__('Users'))
                    ->relationship('users', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
                SelectFilter::make(__('Roles'))
                    ->relationship('roles', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
