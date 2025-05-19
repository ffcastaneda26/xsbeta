<?php

namespace App\Filament\Resources;

use App\Models\Country;
use App\Models\State;
use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompanyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\CompanyResource\RelationManagers\LabelsRelationManager;
use App\Models\TypeTaxPayer;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 36;

    public static function getNavigationLabel(): string
    {
        return __('Companies');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Companies');
    }

    public static function getModelLabel(): string
    {
        return __('Company');
    }

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make(__('Generals'))->schema([
                        Forms\Components\Grid::make(2) // Divide en dos columnas
                            ->schema([
                                // Columna izquierda: name, short, url_company
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->translateLabel()
                                        ->maxLength(100)
                                        ->live(onBlur: true)
                                        ->unique(ignoreRecord: true)
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('short')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->translateLabel()
                                        ->maxLength(20),
                                    Forms\Components\TextInput::make('url_company')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->rules(['required', 'regex:/^[^ ]+$/'])
                                        ->label(__('Url Company (without spaces)'))
                                        ->maxLength(50)
                                        ->required(),
                                    // TODO:: Una vez que tenga cuentas contables debe ser "disabled"
                                    Forms\Components\TextInput::make('account_structure')
                                        ->nullable()
                                        ->label(__('Accounting Account Structure'))
                                        ->maxLength(50)
                                        ->columnSpanFull()
                                        ->regex('/^[1-9]+(-[1-9]+)*$/')
                                        ->validationMessages([
                                            'regex' => __('The account structure must consist of numbers from 1 to 9 separated by hyphens, e.g., 999-99-999-9999, and cannot start or end with a hyphen.'),
                                        ])
                                        ->hint('Segmentos y longitud de los mismos separados por guiones, por ejemplo: 3-2-3-4 indica que las cuentas serán 999-99-999-9999'),
                                ])->columns(2),
                                // Columna derecha: email, phone, active
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->translateLabel()
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->tel()
                                        ->translateLabel()
                                        ->maxLength(15)
                                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                                    Forms\Components\Toggle::make('active')
                                        ->translateLabel()
                                        ->visible(Auth::user()->email == 'admin@contuvo.com')
                                        ->required(),
                                ]),
                            ]),
                    ]),

                    Forms\Components\Wizard\Step::make(__('Ubication'))->schema([
                        Forms\Components\Grid::make(2) // Divide en dos columnas
                            ->schema([
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\Select::make('country_id')
                                        ->relationship('country', 'name')
                                        ->translateLabel()
                                        ->live()
                                        ->required()
                                        ->searchable()
                                        ->loadingMessage(__('Loading countries...'))
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('state_id', null);
                                            $set('city_id', null);
                                            $set('type_tax_payer_id', null);
                                        }),
                                    Forms\Components\Select::make('state_id')
                                        ->translateLabel()
                                        ->required()
                                        ->reactive()
                                        ->default(env('APP_DEFAULT_STATE_ID', 2824))
                                        ->options(function (callable $get) {
                                            $country = Country::find($get('country_id'));
                                            if (!$country) {
                                                return [];
                                            }
                                            return $country->states->sortBy('name')->pluck('name', 'id');
                                        })
                                        ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                                    Forms\Components\Select::make('city_id')
                                        ->translateLabel()
                                        ->required()
                                        ->reactive()
                                        ->default(env('APP_DEFAULT_CITY_ID', 19111))
                                        ->options(function (callable $get) {
                                            $state = State::find($get('state_id'));
                                            if (!$state) {
                                                return [];
                                            }
                                            return $state->cities->sortBy('name')->pluck('name', 'id');
                                        }),
                                    Forms\Components\TextInput::make('municipality')
                                        ->translateLabel()
                                        ->maxLength(100),
                                    Forms\Components\TextInput::make('colony')
                                        ->translateLabel()
                                        ->inlineLabel()
                                        ->maxLength(100)->columnSpanFull(),

                                    Forms\Components\Select::make('type_tax_payer_id')
                                        ->translateLabel()
                                        ->required(function (Get $get) {
                                            $countryId = $get('country_id');
                                            return $countryId && TypeTaxPayer::where('country_id', $countryId)->exists();
                                        })
                                        ->reactive()
                                        ->options(function (callable $get) {
                                            $countryId = $get('country_id');
                                            if (!$countryId) {
                                                return [];
                                            }
                                            $options = TypeTaxPayer::where('country_id', $countryId)
                                                ->orderBy('name')
                                                ->pluck('name', 'id')
                                                ->toArray();
                                            return $options ?: ['' => __('No tax payer types available for this country')];
                                        })
                                        ->searchable()
                                        ->loadingMessage(__('Loading tax payer types...')),
                                    Forms\Components\TextInput::make('tax_id')
                                        ->translateLabel()
                                        ->label(function (Get $get) {
                                            $countryId = $get('country_id');
                                            $tax = $countryId ? Tax::where('country_id', $countryId)->first() : null;
                                            return $tax ? $tax->name : __('Tax ID (Disabled)');
                                        })
                                        ->required(function (Get $get) {
                                            $countryId = $get('country_id');
                                            return $countryId && Tax::where('country_id', $countryId)->exists();
                                        })
                                        ->disabled(function (Get $get) {
                                            $countryId = $get('country_id');
                                            return !$countryId || !Tax::where('country_id', $countryId)->exists();
                                        })
                                        ->reactive()
                                        ->unique(ignoreRecord: true)
                                        ->rules(function (Get $get) {
                                            $countryId = $get('country_id');
                                            $tax = $countryId ? Tax::where('country_id', $countryId)->first() : null;
                                            $rules = [];

                                            if ($tax) {
                                                // Validación de regex si existe
                                                if ($tax->regex) {
                                                    $rules[] = function ($attribute, $value, $fail) use ($tax) {
                                                        if (!preg_match('/' . $tax->regex . '/', $value)) {
                                                            $fail(__('El formato del ' . $tax->name . ' no es válido', [
                                                                'attribute' => __('Tax ID'),
                                                                'tax_name' => $tax->name,
                                                            ]));
                                                        }
                                                    };
                                                }

                                                // Validación de max_length y min_length desde la tabla taxes
                                                if ($tax->max_length) {
                                                    $rules[] = 'max:' . $tax->max_length;
                                                }
                                                if ($tax->min_length) {
                                                    $rules[] = 'min:' . $tax->min_length;
                                                }
                                            }

                                            return $rules;
                                        })
                                        ->validationMessages([
                                            'required' => 'El :attribute es obligatorio.',
                                            'unique' => 'El :attribute ya existe.',
                                            'max' => __('El :attribute no debe exceder los :max caracteres.', ['attribute' => __('Tax ID')]),
                                            'min' => __('El :attribute debe tener al menos :min caracteres.', ['attribute' => __('Tax ID')]),
                                        ]),

                                ])->columns(2),
                                // Columna derecha: address, num_ext, num_int, zipcode, timezone_id
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\TextInput::make('address')
                                        ->translateLabel()
                                        ->maxLength(80)
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('num_ext')
                                        ->translateLabel()
                                        ->maxLength(6),
                                    Forms\Components\TextInput::make('num_int')
                                        ->translateLabel()
                                        ->maxLength(6),
                                    Forms\Components\TextInput::make('zipcode')
                                        ->translateLabel()
                                        ->maxLength(5),

                                    Forms\Components\Select::make('timezone_id')
                                        ->relationship('timezone', 'time_zone')
                                        ->translateLabel()
                                        ->searchable()
                                        ->preload()
                                        ->default(env('APP_DEFAULT_TIME_ZONE_ID', 175))
                                        ->loadingMessage(__('Loading Timezones...'))
                                        ->required(),

                                ])->columns(),
                            ]),
                    ]),

                    Forms\Components\Wizard\Step::make(__('Logo'))->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->translateLabel()
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend(time() . '_'),
                            )->directory('companies')
                            ->columnSpanFull(),
                    ]),
                ])->skippable()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->translateLabel(),
                Tables\Columns\ImageColumn::make('logo')
                    ->circular()
                    ->size(50)
                    ->translateLabel(),


                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('num_ext')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('num_int')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state_id')
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('municipality')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('colony')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zipcode')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Actions\EditAction::make()->button(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LabelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
