<?php

namespace App\Filament\Resources;

use Hash;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\CompaniesRelationManager;

class UserResource extends Resource
{

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {

        if (filament()->getCurrentPanel()->getId() === 'admin') {
           return true;
        }


        return Auth::user()->companies->contains(Filament::getTenant());
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }


    public static function getModelLabel(): string
    {
        return __('User');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Users');
    }

    public static function getNavigationBadge(): ?string
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            return static::getModel()::whereHas('companies',function(Builder $query) use($tenant){
                $query->where('companies.id', $tenant->id);
            })->count();
        }

        return static::getModel()::count();
    }
    public static function getNavigationGroup(): string
    {
        return __('Security');
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Detectar si estamos en el panel CompanyPanelProvider
        if (filament()->getCurrentPanel()->getId() === 'company') {
            $tenant = filament()->getTenant();
            $query->whereHas('companies', function (Builder $subQuery) use ($tenant) {
                $subQuery->where('companies.id', $tenant->id);
            });
        }

        return $query;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->minLength(length: 5)
                            ->maxLength(100)
                            ->translateLabel(),
                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->translateLabel()
                            ->maxLength(100)
                            ->minLength(5),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->translateLabel()
                            ->maxLength(30)
                            ->minLength(8)
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                        Forms\Components\Toggle::make('active'),
                    ])->columns(2),


                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        // Obtener el tenant actual
        $tenant = Filament::getTenant();

        // Definir las columnas base
        $columns = [
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->translateLabel(),
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->translateLabel(),
            Tables\Columns\IconColumn::make('active')
                ->boolean()
                ->translateLabel(),
            Tables\Columns\TextColumn::make('email_verified_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('two_factor_confirmed_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        // Agregar la columna 'companies' solo si NO hay un tenant
        if (!$tenant) {
            $columns[] = Tables\Columns\TextColumn::make('companies')
                ->label('Empresas')
                ->getStateUsing(function (User $record): string {
                    return $record->companies->pluck('name')->implode('<br>');
                })
                ->html()
                ->sortable()
                ->searchable()
                ->wrap();
        }

        return $table
            ->columns($columns)
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('companies', 'name')
                    ->preload()
                    ->translateLabel()
                    ->searchable(),
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
            CompaniesRelationManager::class,
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
