<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->minLength(length: 5)
                        ->maxLength(100)
                        ->label('Nombre'),

                    TextInput::make('email')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->label('Correo Electrónico')
                        ->maxLength(100)
                        ->minLength(5),

                ])->columns(2),
                Group::make()->schema([
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->maxLength(30)
                        ->minLength(8)
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context): bool => $context === 'create'),

                ])->columns(2),
                Group::make()->schema([
                    Select::make('roles')
                        ->relationship('roles', 'name', fn (Builder $query) => $query->where('name', '!=', 'Super Admin'))
                        ->preload()
                        ->searchable()
                        ->label('Roles'),
                    Toggle::make('active')->label('¿Activo?'),

                ])->columns(2),
            ]);
    }
}
