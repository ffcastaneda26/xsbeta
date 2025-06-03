<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingAccountResource\Pages;
use App\Models\AccountingAccount;
use App\Models\AccountType;
use App\Models\AccountSubType;
use App\Models\AccountingCategory;
use App\Models\AccountingSingleAccount;
use App\Rules\AccountStructureRule;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AccountingAccountResource extends Resource
{
    protected static ?string $model = AccountingAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?int $navigationSort = 36;

    public static function getLabel(): ?string
    {
        return __('Accounting Account');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Accounting Accounts');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
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
                                            return AccountSubType::where('account_type_id', $accountTypeId)
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
                                                $subtype = AccountSubType::find($accountSubtypeId);
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
                                            return !($get('account_type_id') && $get('account_subtype_id'));
                                        }),
                                    Forms\Components\TextInput::make('ledger_account')
                                        ->label(__('Ledger'))
                                        ->readOnly()
                                        // ->dehydrated(false)
                                        ->extraInputAttributes(['style' => 'background-color: #f3f4f6;']),
                                ]),


                        ]),
                    ]),

                // Categorías a las que pertenece
                Forms\Components\Group::make()->schema([
                    Forms\Components\CheckboxList::make('categories')
                        ->label(__('Belongs To'))
                        ->relationship('categories', 'name')
                        ->options(function () {
                            return AccountingCategory::query()->pluck('name', 'id');
                        })
                        ->nullable()
                        ->disabled(function (callable $get) {
                            return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                        })
                        ->extraAttributes(['class' => 'flex flex-row gap-4 flex-wrap']),
                ])->columnSpanFull(),

                Forms\Components\Grid::make(3) // Grid de 6 columnas
                    ->schema([
                        // Forms\Components\Select::make('accounting_single_account_id')
                        //     ->translateLabel()
                        //     ->options(function () {
                        //         return AccountingSingleAccount::query()->pluck('name', 'id');
                        //     })
                        //     ->nullable()
                        //     ->inlineLabel()
                        //     ->disabled(function (callable $get) {
                        //         return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                        //     }),
                        Forms\Components\Select::make('accounting_single_account_id')
                            ->translateLabel()
                            ->options(function (callable $get) {
                                $accountTypeId = $get('account_type_id');
                                if (!$accountTypeId) {
                                    return [];
                                }

                                return AccountingSingleAccount::where('account_type_id', $accountTypeId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->nullable()
                            ->inlineLabel()
                            ->disabled(function (callable $get) {
                                return !($get('account_type_id') && $get('account_subtype_id') && $get('code') && $get('name'));
                            })
                            ->reactive(),
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
                Tables\Columns\TextColumn::make('debit')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('credit')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('balance')
                    ->translateLabel()
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                    // TODO:: Si se desea mostrar el valor absoluto
                    // ->formatStateUsing(fn ($state) => abs($state)),
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
                Tables\Filters\Filter::make('account_type_and_subtype')
                    ->form([
                        Forms\Components\Select::make('account_type_id')
                            ->label(__('Account Type'))
                            ->options(AccountType::query()->pluck('name', 'id'))
                            ->live(),
                        Forms\Components\Select::make('account_subtype_id')
                            ->label(__('Account Subtype'))
                            ->options(function (callable $get) {
                                $accountTypeId = $get('account_type_id');
                                if (!$accountTypeId) {
                                    return [];
                                }
                                return AccountSubType::query()
                                    ->where('account_type_id', $accountTypeId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->visible(fn(callable $get): bool => (bool) $get('account_type_id'))
                            ->live(), // Use live() for dynamic updates
                    ])
                    ->query(function (Builder $query, array $data) {
                        // Apply the account_type_id filter
                        if (!empty($data['account_type_id'])) {
                            $query->where('account_type_id', $data['account_type_id']);
                        }
                        // Apply the account_subtype_id filter
                        if (!empty($data['account_subtype_id'])) {
                            $query->where('account_subtype_id', $data['account_subtype_id']);
                        }
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (!empty($data['account_type_id'])) {
                            $accountType = AccountType::find($data['account_type_id']);
                            $indicators[] = __('Account Type') . ': ' . ($accountType->name ?? 'Unknown');
                        }
                        if (!empty($data['account_subtype_id'])) {
                            $AccountSubType = AccountSubType::find($data['account_subtype_id']);
                            $indicators[] = __('Account Subtype') . ': ' . ($AccountSubType->name ?? 'Unknown');
                        }
                        return $indicators;
                    }),
                Tables\Filters\Filter::make('has_balance')
                    ->form([
                        Forms\Components\Toggle::make('has_balance')
                            ->label(__('Has Balance'))
                            ->default(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['has_balance']) {
                            $query->where('balance', '!=', 0);
                        }
                    })
                    ->indicateUsing(function (array $data): array {
                        if ($data['has_balance']) {
                            return [__('Accounts with balance')];
                        }
                        return [];
                    }),
                Tables\Filters\Filter::make('has_movements')
                    ->form([
                        Forms\Components\Toggle::make('has_movements')
                            ->label(__('Has Movements'))
                            ->default(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['has_movements']) {
                            $query->whereHas('items');
                        }
                    })
                    ->indicateUsing(function (array $data): array {
                        if ($data['has_movements']) {
                            return [__('Accounts with movements')];
                        }
                        return [];
                    }),
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
