<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountSubtypeResource\Pages;
use App\Filament\Company\Resources\AccountSubtypeResource\RelationManagers;
use App\Models\AccountSubtype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountSubtypeResource extends Resource
{
    protected static ?string $model = AccountSubtype::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 22;
    public static function getNavigationLabel(): string
    {
        return __('Accoount Subtypes');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Accoount Subtypes');

    }
    public static function getModelLabel(): string
    {
        return __('Accoount Subtype');
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
                Forms\Components\RichEditor::make('description')
                    ->translateLabel()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accountType.name')
                    ->translateLabel()
                    ->label(__('Account Type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->translateLabel()
                    ->searchable()
                    ->html()
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_type_id')
                    ->relationship('type', 'name')
                    ->preload()
                    ->translateLabel()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
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
            'index' => Pages\ListAccountSubtypes::route('/'),
            'create' => Pages\CreateAccountSubtype::route('/create'),
            'edit' => Pages\EditAccountSubtype::route('/{record}/edit'),
        ];
    }


}
