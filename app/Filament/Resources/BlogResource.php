<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Blog;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BlogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BlogResource\RelationManagers;
use BladeUI\Icons\Components\Icon;
use Faker\Core\Color;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;


    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 30;


    public static function getNavigationLabel(): string
    {
        return __('Blogs');
    }
    public static function getModelLabel(): string
    {
        return __('Blog');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Blogs');
    }

    public static function getNavigationGroup(): string
    {
        return __('Blogs');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');

    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->translateLabel()
                        ->maxLength(255)
                        ->columnSpanFull()
                        // ->live(onBlur: true)
                        ->unique(ignoreRecord: true),
                        // ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('subtitle')
                        ->translateLabel()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->translateLabel()
                        ->directory('blogs')
                        ->preserveFilenames(),
                ])->columns(1),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\DatePicker::make('date')
                            ->translateLabel()
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->label(__('Author'))
                            ->columnSpan(2)
                            ->visible(fn() => Auth::user()->hasrole('Administrador') || Auth::user()->hasrole('Super Admin'))
                            ->required(fn() => Auth::user()->hasrole('Administrador') || Auth::user()->hasrole('Super Admin')),

                        Forms\Components\Toggle::make('active')
                            ->required()
                            ->columnStart(fn() => Auth::user()->hasrole('Administrador') || Auth::user()->hasrole('Super Admin') ? 0 : 4)
                            ->default(true),
                        Forms\Components\Select::make('type_id')
                            ->relationship('type', 'name')
                            ->translateLabel()
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->translateLabel()
                            ->columnSpan(2)
                            ->required(),
                    ])->columns(4)
                    // ->visible(fn() => !Auth::user()->hasrole('Administrador')),

                ]),
                Forms\Components\Textarea::make('introduction')
                    ->label(__('Introduction'))
                    ->rows(4)
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\MarkdownEditor::make('description')
                    ->required()
                    ->translateLabel()
                    ->columnSpanFull()
                    ->minHeight('500px'),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('title')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->translateLabel()
                    ->date()
                    // ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->translateLabel()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean()->translateLabel()->icon('heroicon-s-check' ),
                Tables\Columns\TextColumn::make('subtitle')->translateLabel()->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type.name')->translateLabel()->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')->translateLabel()->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('introduction')->translateLabel()->sortable()->limit(50)->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')->translateLabel()->sortable()->limit(50)->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('slug')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->since()->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenlabel(true)->color('warning')->tooltip(__('Edit')),
                Tables\Actions\ViewAction::make()->hiddenlabel(true)->color('primary')->tooltip(__('View')),
                Tables\Actions\DeleteAction::make()->hiddenlabel(true)->color('danger')->tooltip(__('Delete')),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
