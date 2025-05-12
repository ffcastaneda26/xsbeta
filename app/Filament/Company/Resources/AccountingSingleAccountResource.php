<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingSingleAccountResource\Pages;
use App\Filament\Company\Resources\AccountingSingleAccountResource\RelationManagers;
use App\Models\AccountingSingleAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountingSingleAccountResource extends Resource
{
    protected static ?string $model = AccountingSingleAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 23;
    public static function getNavigationLabel(): string
    {
        return __('Single Accounts');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Single Accounts');

    }
    public static function getModelLabel(): string
    {
        return __('Single Account');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('company_id', filament()->getTenant()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Select::make('account_type_id')
                        ->relationship('accountType', 'name')
                        ->required()
                        ->translateLabel()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->translateLabel()
                        ->maxLength(100),
                ])->columns(2),
                Forms\Components\Group::make()->schema([

                    Forms\Components\TextInput::make('code')
                        ->maxLength(255)
                        ->translateLabel(),

                ]),
                Forms\Components\RichEditor::make('description')
                    ->translateLabel()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
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
            'index' => Pages\ListAccountingSingleAccounts::route('/'),
            'create' => Pages\CreateAccountingSingleAccount::route('/create'),
            'edit' => Pages\EditAccountingSingleAccount::route('/{record}/edit'),
        ];
    }
}
