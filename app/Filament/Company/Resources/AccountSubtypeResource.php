<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountSubtypeResource\Pages;
use App\Filament\Company\Resources\AccountSubtypeResource\RelationManagers;
use App\Models\AccountSubType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountSubtypeResource extends Resource
{
    protected static ?string $model = AccountSubType::class;

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
                Forms\Components\Grid::make(2) // Create a 2-column grid for the overall layout
                    ->schema([
                        // Left column
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('account_type_id')
                                    ->relationship('accountType', 'name')
                                    ->required()
                                    ->translateLabel()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Grid::make(8) // Nested grid with 4 columns for name and code
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->translateLabel()
                                            ->maxLength(100)
                                            ->regex('/^\d{2}\s/')
                                            ->validationMessages([
                                                'regex' => __('The name must start with two digits, a space, and then the description.'),
                                            ])
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                // Extraer los dos primeros dígitos del campo 'name' y asignarlos a 'code'
                                                if (preg_match('/^\d{2}/', $state, $matches)) {
                                                    $set('code', $matches[0]);
                                                } else {
                                                    $set('code', null);
                                                }
                                            })
                                            ->columnSpan(7), // 75% of the container (3/4 columns)
                                        Forms\Components\TextInput::make('code')
                                            ->required()
                                            ->translateLabel()
                                            ->readOnly()
                                            ->maxLength(4)
                                            ->extraInputAttributes(['style' => 'background-color: #f3f4f6;']) // Gray background
                                            ->unique(
                                                table: AccountSubType::class,
                                                column: 'code',
                                                ignorable: fn($record) => $record,
                                                modifyRuleUsing: function ($rule, $get) {
                                                    return $rule->where('company_id', filament()->getTenant()->id)
                                                        ->where('account_type_id', $get('account_type_id'));
                                                }
                                            )
                                            ->validationMessages([
                                                'unique' => __('A subtype with that code already exists for this account type.'),
                                            ])
                                            ->columnSpan(1), // 25% of the container (1/4 columns)
                                    ]),
                            ])
                            ->columnSpan(1), // Left column takes 1/2 of the main grid
                        // Right column
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->translateLabel()
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1),
                    ]),
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
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
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
                    ->limit(50)



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
