<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Pest\ArchPresets\Security;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 11;
    // protected static ?string $cluster = Security::class;

    public static function getNavigationGroup(): string
    {
        return __('Security');
    }
    public static function getNavigationLabel(): string
    {
        return __('Roles');
    }


    public static function getModelLabel(): string
    {
        return __('Role');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Roles');
    }

    // public static function getNavigationGroup(): string {
    //     return __('Security');
    // }

    public static function getNavigationBadge(): ?string
    {
        if(Auth::user()->hasrole('Super Admin')){
            return static::getModel()::count();
        }
        return parent::getEloquentQuery()
            ->where('name','not like','%super%')
            ->count();
    }
    public static function getEloquentQuery(): Builder
    {
        if(Auth::user()->hasrole('Super Admin')){
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('name','not like','%super%');

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
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel()->searchable()->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Usuarios'),
                TextColumn::make('permissions.name')->label('Permisos')

            ])
            ->filters([
                SelectFilter::make(__('Users'))
                    ->relationship('users', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
                SelectFilter::make(__('Permissions'))
                            ->relationship('permissions', 'name')
                            ->translateLabel()
                            ->searchable()
                            ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
