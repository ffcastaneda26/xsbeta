<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuscriptionResource\Pages;
use App\Filament\Resources\SuscriptionResource\RelationManagers;
use App\Models\Suscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuscriptionResource extends Resource
{
    protected static ?string $model = Suscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 35;

    public static function getNavigationLabel(): string
    {
        return __('Suscriptions');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Suscriptions');
    }

    public static function getModelLabel(): string
    {
        return __('Suscription');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Select::make('company_id')
                        ->relationship('company', 'name')
                        ->required()
                        ->translateLabel(),
                    Forms\Components\Select::make('plan_id')
                        ->relationship('plan', 'name')
                        ->required()
                        ->translateLabel(),

                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\DatePicker::make('date')
                        ->required()
                        ->translateLabel(),
                    Forms\Components\TextInput::make('amount')
                        ->required()
                        ->translateLabel(),
                    Forms\Components\DatePicker::make('bill_date')
                        ->required()
                        ->translateLabel(),
                    Forms\Components\TextInput::make('status')
                        ->required()
                        ->translateLabel(),
                ])->columns(2),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                   ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                   ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('bill_date')
                    ->date()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSuscriptions::route('/'),
            'create' => Pages\CreateSuscription::route('/create'),
            'edit' => Pages\EditSuscription::route('/{record}/edit'),
        ];
    }
}
