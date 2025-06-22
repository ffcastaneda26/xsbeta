<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\CostCenterResource\Pages;
use App\Filament\Company\Resources\CostCenterResource\RelationManagers;
use App\Models\CostCenter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostCenterResource extends Resource
{
    protected static ?string $model = CostCenter::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 35;


    public static function getNavigationLabel(): string
    {
        return __('Cost Centers');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Cost Centers');

    }
    public static function getModelLabel(): string
    {
        return __('Cost Center');
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
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->translateLabel()
                        ->maxLength(75)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('code')
                        ->label(__('Code'))
                        ->required()
                        ->rules([
                            'regex:/^\S+$/',
                        ])
                        ->translateLabel()
                        ->maxLength(15)
                        ->minLength(3)
                        ->validationAttribute('code')
                        ->unique(
                            table: CostCenter::class,
                            column: 'code',
                            ignorable: fn(?CostCenter $record) => $record,
                            modifyRuleUsing: function ($rule, $get) {
                                return $rule
                                    ->where('company_id', filament()->getTenant()->id);
                            }
                        )
                        ->validationMessages([
                            'regex' => __('The code must not have spaces'),
                            'required' => 'El :attribute es obligatorio.',
                            'unique' => __('Code already exists'),
                        ]),
                    Forms\Components\Toggle::make('is_active')
                        ->translateLabel()
                        ->required(),

                ])->columns(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),

                ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCostCenters::route('/'),
            'create' => Pages\CreateCostCenter::route('/create'),
            'edit' => Pages\EditCostCenter::route('/{record}/edit'),
        ];
    }
}
