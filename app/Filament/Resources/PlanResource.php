<?php

namespace App\Filament\Resources;

use App\Enums\PlanTypeEnum;
use Filament\Forms;
use App\Models\Plan;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PlanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PlanResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 35;


    public static function getNavigationLabel(): string
    {
        return __('Plans');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Plans');

    }
    public static function getModelLabel(): string
    {
        return __('Plan');
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
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->translateLabel()
                        ->numeric()
                        ->prefix('$'),

                    Forms\Components\TextInput::make('currency')
                        ->required()
                        ->translateLabel()
                        ->maxLength(10)
                        ->default('USD'),

                    Forms\Components\Select::make('plan_type')
                        ->required()
                        ->translateLabel()
                        ->options(
                            collect(PlanTypeEnum::cases())->mapWithKeys(fn($case) => [
                                $case->value => $case->getLabel()
                            ])->toArray()
                        ),
                    Forms\Components\TextInput::make('days')
                        ->required()
                        ->translateLabel()
                        ->numeric()
                        ->default(30),
                    Forms\Components\FileUpload::make('image')
                        ->required()
                        ->translateLabel()
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend(time() . '_'),
                        )->directory('plans')
                        ->columnSpanFull(),

                ])->columns(2),
                Forms\Components\Group::make()->schema([

                    Forms\Components\RichEditor::make('description')
                        ->columnSpanFull()
                        ->translateLabel(),

                    Forms\Components\Toggle::make('active')
                        ->required()
                        ->translateLabel()

                ]),





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
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('plan_type')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('days')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\ImageColumn::make('image')
                    ->translateLabel()
                    ->circular(),

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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
