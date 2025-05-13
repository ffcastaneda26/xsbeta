<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingAccountResource\Pages;
use App\Models\AccountingAccount;
use App\Models\AccountType;
use App\Models\AccountSubtype;
use App\Models\AccountingCategory;
use App\Models\AccountingSingleAccount;
use App\Rules\AccountStructureRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AccountingAccountResource extends Resource
{
    protected static ?string $model = AccountingAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    public static function getLabel(): ?string
    {
        return __('Accounting Account');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Accounting Accounts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2) // Grid de 6 columnas
                    ->schema([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\Grid::make(2) // Grid de 6 columnas
                                ->schema([
                                    Forms\Components\Select::make('account_type_id')
                                        ->translateLabel()
                                        ->options(AccountType::query()->pluck('name', 'id'))
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set) {
                                            $set('account_subtype_id', null); // Reset subtype when type changes
                                            $set('ledger_account', null); // Reset ledger account
                                        }),
                                    Forms\Components\Select::make('account_subtype_id')
                                        ->translateLabel()
                                        ->options(function (callable $get) {
                                            $accountTypeId = $get('account_type_id');
                                            if (!$accountTypeId) {
                                                return [];
                                            }
                                            return AccountSubtype::where('account_type_id', $accountTypeId)
                                                ->pluck('name', 'id');
                                        })
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set) {
                                            $set('ledger_account', null); // Reset ledger account
                                        }),
                                ]),


                        ]),
                        Forms\Components\Group::make()->schema([
                            Forms\Components\Grid::make(5) // Grid de 6 columnas
                                ->schema([
                                    Forms\Components\TextInput::make('code')
                                        ->label(__('Code'))
                                        ->required()
                                        ->rules([
                                            'regex:/^\d{1,10}$/',
                                            new AccountStructureRule(filament()->getTenant()),
                                        ])
                                        ->disabled(function (callable $get) {
                                            return !($get('account_type_id') && $get('account_subtype_id'));
                                        })
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (callable $set, $state, $get) {
                                            // Calcula o integra la cuenta de mayor
                                            $accountTypeId = $get('account_type_id');
                                            $accountSubtypeId = $get('account_subtype_id');
                                            if ($accountTypeId && $accountSubtypeId && $state) {
                                                $subtype = AccountSubtype::find($accountSubtypeId);
                                                $ledger = sprintf(
                                                    '%s%s%s',
                                                    $accountTypeId,
                                                    str_pad($subtype->code, 2, '0', STR_PAD_LEFT),
                                                    $state
                                                );
                                                $set('ledger_account', $ledger);
                                            }
                                        })
                                        ->validationAttribute('code')
                                        ->unique(
                                            table: AccountingAccount::class,
                                            column: 'code',
                                            ignorable: fn(?AccountingAccount $record) => $record,
                                            modifyRuleUsing: function ($rule, $get) {
                                                return $rule
                                                    ->where('company_id', filament()->getTenant()->id)
                                                    ->where('account_type_id', $get('account_type_id'))
                                                    ->where('account_subtype_id', $get('account_subtype_id'));
                                            }
                                        )
                                        ->validationMessages([
                                            'required' => 'El :attribute es obligatorio.',
                                            'unique' => __('Code already exists'),
                                        ]),

                                    Forms\Components\TextInput::make('name')
                                        ->translateLabel()
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(3)
                                        ->disabled(function (callable $get) {
                                            return !($get('account_type_id') && $get('account_subtype_id') );
                                        }),
                                    Forms\Components\TextInput::make('ledger_account')
                                        ->label(__('Ledger'))
                                        ->readOnly()
                                        // ->dehydrated(false)
                                        ->extraInputAttributes(['style' => 'background-color: #f3f4f6;']),
                                ]),


                        ]),
                    ]),
                Forms\Components\Grid::make(4) // Grid de 6 columnas
                    ->schema([
                        Forms\Components\CheckboxList::make('categories')
                            ->label(__('Belongs To'))
                            ->relationship('categories', 'name')
                            ->options(function () {
                                return AccountingCategory::query()->pluck('name', 'id');
                            })
                            ->nullable()
                            ->disabled(function (callable $get) {
                                return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                            }),

                        Forms\Components\Select::make('accounting_single_account_id')
                            ->translateLabel()
                            ->options(function () {
                                return AccountingSingleAccount::query()->pluck('name', 'id');
                            })
                            ->nullable()
                            ->disabled(function (callable $get) {
                                return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                            }),

                        Forms\Components\Toggle::make('is_analysis_code')
                            ->label(__('Is Analysis Code'))
                            ->default(false)
                            ->disabled(function (callable $get) {
                                return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                            }),

                        Forms\Components\Toggle::make('is_cost_center_required')
                            ->label(__('Is Cost Center Required'))
                            ->default(false)
                            ->disabled(function (callable $get) {
                                return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                            }),
                        // Forms\Components\Grid::make(3) // Grid de 6 columnas
                        //     ->schema([

                        //     ]),
                    ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\RichEditor::make('description')
                        ->translateLabel()
                        ->columnSpanFull(),


                    Forms\Components\Select::make('parent_id')
                        ->label(__('Parent Account'))
                        ->options(function () {
                            return AccountingAccount::query()->pluck('name', 'id');
                        })
                        ->nullable()
                        ->visible(false),
                ])->visible(false),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtype.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ledger_account')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_analysis_code')
                    ->boolean()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_cost_center_required')
                    ->boolean()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_type_id')
                    ->label(__('Account Type'))
                    ->options(AccountType::query()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('account_subtype_id')
                    ->label(__('Account Subtype'))
                    ->options(AccountSubtype::query()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountingAccounts::route('/'),
            'create' => Pages\CreateAccountingAccount::route('/create'),
            'edit' => Pages\EditAccountingAccount::route('/{record}/edit'),
        ];
    }
}
