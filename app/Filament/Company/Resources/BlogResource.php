<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\BlogResource\Pages;
use App\Filament\Company\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?string $tenantOwnershipRelationship = 'company';
    protected static ?int $navigationSort = 13;

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

    public static function getNavigationBadge(): ?string
    {
        $count = Blog::count();
        return $count > 0 ? (string) $count : '0';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Blog::count() > 0 ? 'primary' : 'danger';
    }
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('blog-images')
                    ->nullable(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publicar')
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Fecha de publicación')
                    ->nullable(),
                Forms\Components\Select::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
