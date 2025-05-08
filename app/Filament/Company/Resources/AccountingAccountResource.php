<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\AccountingAccountResource\Pages;
use App\Filament\Company\Resources\AccountingAccountResource\RelationManagers;
use App\Models\AccountingAccount;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class AccountingAccountResource extends Resource
{
    protected static ?string $model = AccountingAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 21;

    public static function getNavigationLabel(): string
    {
        return __('Accounting Accounts');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Accounting Accounts');
    }

    public static function getModelLabel(): string
    {
        return __('Accounting Account');
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
                    Forms\Components\Select::make('account_type_id')
                        ->relationship('accountType', 'name')
                        ->required()
                        ->translateLabel(),
                    Forms\Components\Select::make('parent_id')
                        ->label(__('Parent Account'))
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->where('company_id', Filament::getTenant()->id)
                                ->whereRaw('code LIKE ?', ['%-000'])
                        )
                        ->nullable()
                        ->reactive()
                        ->translateLabel(),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->translateLabel()
                        ->reactive()
                        // TODO:: Validar que solo esté duplicado para la misma empresa
                        ->unique(ignoreRecord: true)
                        ->rules([
                            'required',
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    $company = Filament::getTenant();
                                    if (!$company || !static::validateAccountStructure($value, $company->account_structure)) {
                                        $fail(__('Account structure is incorrect'));
                                        \Filament\Notifications\Notification::make()
                                            ->title(__('Account structure is incorrect'))
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                };
                            },
                        ])
                        ->validationMessages([
                            'required' => __('The account code is required.'),
                        ])
                        ->helperText(function () {
                            $company = Filament::getTenant();
                            return $company ? __('Account code must follow structure:') . static::formatAccountStructure($company->account_structure) : '';
                        }),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->translateLabel()
                        ->maxLength(100),
                    Forms\Components\Toggle::make('active')
                        ->translateLabel()
                        ->label(function () {
                            return app()->getLocale() === 'en' ? 'Active?' : '¿Activa?';
                        })
                        ->default(true),
                ])->columns(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\RichEditor::make('description')
                        ->translateLabel()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable()->searchable()->translateLabel(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->translateLabel(),
                Tables\Columns\TextColumn::make('accountType.name')->sortable()->translateLabel(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('Parent Account'))
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAccountingAccounts::route('/'),
            'create' => Pages\CreateAccountingAccount::route('/create'),
            'edit' => Pages\EditAccountingAccount::route('/{record}/edit'),
        ];
    }

    protected static function validateAccountStructure(string $code, string $structure): bool
    {
        $segments = explode('-', $structure);
        $codeSegments = explode('-', $code);

        // Validate number of segments
        if (count($segments) !== count($codeSegments)) {
            return false;
        }

        // Validate each segment
        foreach ($segments as $index => $length) {
            if (!isset($codeSegments[$index])) {
                return false;
            }

            $segment = $codeSegments[$index];

            // Check if segment contains only digits
            if (!ctype_digit($segment)) {
                return false;
            }

            // Check segment length
            if (strlen($segment) != $length) {
                return false;
            }

            // Check if segment is within valid range
            $maxValue = (int) str_repeat('9', $length);
            $segmentValue = (int) $segment;
            if ($segmentValue > $maxValue) {
                return false;
            }
        }

        return true;
    }

    protected static function formatAccountStructure(string $structure): string
    {
        $segments = explode('-', $structure);
        $formattedSegments = array_map(function ($length) {
            return str_repeat('9', (int) $length);
        }, $segments);
        return implode('-', $formattedSegments);
    }
}
