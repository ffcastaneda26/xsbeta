<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeZoneResource\Pages;
use App\Filament\Resources\TimeZoneResource\RelationManagers;
use App\Models\TimeZone;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeZoneResource extends Resource
{
    protected static ?string $model = TimeZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 34;


    public static function getNavigationLabel(): string
    {
        return __('Time Zones');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Time Zones');

    }
    public static function getModelLabel(): string
    {
        return __('Time Zone');
    }



    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('time_zone')
                    ->required()
                    ->translateLabel()
                    ->maxLength(50)
                    ->disabled(),
                TextInput::make('continent')
                    ->required()
                    ->translateLabel()
                    ->maxLength(50)
                    ->disabled(),
                TextInput::make('zone')
                    ->required()
                    ->translateLabel()
                    ->maxLength(50)
                    ->disabled(),
                Toggle::make('use')
                    ->translateLabel()
                    ->inline(false)
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('time_zone')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('continent')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('zone')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('use')->translateLabel()->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
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
            'index' => Pages\ListTimeZones::route('/'),
            'create' => Pages\CreateTimeZone::route('/create'),
            'edit' => Pages\EditTimeZone::route('/{record}/edit'),
        ];
    }
}
